#!/usr/bin/env python3
# ------------------------------------------------------------
# Script: monitor_servicios.py
# Proyecto ASO - Monitorización de Servicios Web
# Apache + Nginx
# ------------------------------------------------------------

import os
import subprocess
import socket
import datetime
import time
import csv

# =========================
# VARIABLES
# =========================
SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__))
DATE_ISO = datetime.datetime.now().strftime("%Y-%m-%dT%H:%M:%S%z")
HOSTNAME = socket.gethostname()

IP_LOCAL = subprocess.getoutput("hostname -I").split()[0]

LOG_DIR = os.path.join(SCRIPT_DIR, "logs")
LOG_FILE = os.path.join(LOG_DIR, "monitor.csv")

SERVICIOS_WEB = ["apache2", "nginx"]
PUERTOS = ["80", "443"]

os.makedirs(LOG_DIR, exist_ok=True)

# Crear cabecera CSV si no existe
if not os.path.isfile(LOG_FILE):
    with open(LOG_FILE, "w", newline="") as f:
        writer = csv.writer(f)
        writer.writerow([
            "fecha", "host", "ip", "servicio", "estado_servicio",
            "puerto", "http_code", "http_time",
            "cpu_load", "ram_pct", "disco_pct",
            "resultado", "descripcion"
        ])

# =========================
# FUNCIONES
# =========================
def run(cmd):
    return subprocess.getoutput(cmd)

def check_servicio(servicio):
    result = subprocess.run(
        ["systemctl", "is-active", "--quiet", servicio]
    )
    return result.returncode  # 0 = activo

def check_reinicios(servicio):
    cmd = f"journalctl -u {servicio} --since '1 hour ago' | grep -i started | wc -l"
    return run(cmd)

def check_puerto():
    for p in PUERTOS:
        if subprocess.call(f"ss -lnt | grep -q ':{p} '", shell=True) == 0:
            return p
    return "NO"

def check_http():
    out = run("/usr/bin/curl -s -o /dev/null -w '%{http_code} %{time_total}' http://localhost")
    code, t = out.split()
    return int(code), float(t)

def check_cpu():
    return run("uptime | awk -F'load average:' '{print $2}' | cut -d',' -f1 | xargs")

def check_ram():
    return int(run("free | awk '/Mem:/ { printf \"%.0f\", $3/$2 * 100 }'"))

def check_disco():
    return int(run("df / | awk 'NR==2 { gsub(\"%\",\"\"); print $5 }'"))

def catalogar_error(estado, http_code, ram, disco):
    if estado != 0:
        return "CRIT: Servicio detenido"
    elif http_code >= 500:
        return f"CRIT: Error servidor web HTTP {http_code}"
    elif disco >= 90:
        return f"CRIT: Disco casi lleno ({disco}%)"
    elif http_code >= 400:
        return f"WARN: HTTP {http_code}"
    elif ram >= 80:
        return f"WARN: RAM alta ({ram}%)"
    elif disco >= 80:
        return f"WARN: Disco alto ({disco}%)"
    else:
        return "OK: Todo correcto"

def calcular_resultado(estado, http_code, ram, disco):
    if estado != 0 or http_code >= 500 or disco >= 90:
        return "CRIT"
    elif http_code >= 400 or ram >= 80 or disco >= 80:
        return "WARN"
    else:
        return "OK"

def log_csv(row):
    with open(LOG_FILE, "a", newline="") as f:
        writer = csv.writer(f)
        writer.writerow(row)

# =========================
# CHEQUEO COMPLETO
# =========================
def ejecutar_chequeo():
    cpu_load = check_cpu()
    ram_pct = check_ram()
    disco_pct = check_disco()

    resultado_global = "OK"

    for servicio in SERVICIOS_WEB:
        estado = check_servicio(servicio)
        reinicios = check_reinicios(servicio)
        puerto = check_puerto()
        http_code, http_time = check_http()

        resultado = calcular_resultado(estado, http_code, ram_pct, disco_pct)
        descripcion = catalogar_error(estado, http_code, ram_pct, disco_pct)

        if resultado == "CRIT":
            resultado_global = "CRIT"
        elif resultado == "WARN" and resultado_global == "OK":
            resultado_global = "WARN"

        log_csv([
            DATE_ISO, HOSTNAME, IP_LOCAL, servicio,
            estado, puerto, http_code, http_time,
            cpu_load, ram_pct, disco_pct,
            resultado, descripcion
        ])

        print(f"Servicio: {servicio} → {resultado}")
        print(f"Descripción: {descripcion}")
        print(f"Reinicios última hora: {reinicios}")
        print("-" * 30)

    print(f"\nResultado GLOBAL de la ejecución: {resultado_global}\n")

# =========================
# INICIO / PARO
# =========================
def iniciar_servicio(svc):
    subprocess.call(["systemctl", "start", svc])
    time.sleep(1)
    print(f"Servicio {svc} INICIADO ✅")

def parar_servicio(svc):
    subprocess.call(["systemctl", "stop", svc])
    time.sleep(1)
    print(f"Servicio {svc} PARADO 🛑")

# =========================
# MENÚ
# =========================
def menu_manual():
    while True:
        os.system("clear")
        print("===== MONITORIZACIÓN ASO =====")
        print("1) Monitorizar servicios web")
        print("2) Iniciar Apache")
        print("3) Parar Apache")
        print("4) Iniciar Nginx")
        print("5) Parar Nginx")
        print("6) Ver log")
        print("7) Salir\n")

        opt = input("Opción: ")

        if opt == "1":
            ejecutar_chequeo()
            input("ENTER para continuar")
        elif opt == "2":
            iniciar_servicio("apache2")
            input("ENTER para continuar")
        elif opt == "3":
            parar_servicio("apache2")
            input("ENTER para continuar")
        elif opt == "4":
            iniciar_servicio("nginx")
            input("ENTER para continuar")
        elif opt == "5":
            parar_servicio("nginx")
            input("ENTER para continuar")
        elif opt == "6":
            subprocess.call(["less", LOG_FILE])
        elif opt == "7":
            exit(0)
        else:
            print("Opción no válida")
            time.sleep(1)

# =========================
# MAIN
# =========================
import sys
if len(sys.argv) > 1 and sys.argv[1] == "auto":
    ejecutar_chequeo()
else:
    menu_manual()
