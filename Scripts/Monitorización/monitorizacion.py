#!/usr/bin/env python3
# -*- coding: utf-8 -*-

"""
Script de monitorización de servicios (Apache2 principalmente)
Modo manual y modo automático (cron)
"""

import os
import subprocess
import datetime
import sys

# ==============================
# RUTAS DINÁMICAS
# ==============================

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
LOG_DIR = os.path.join(BASE_DIR, "logs")
OUTPUT_DIR = os.path.join(BASE_DIR, "output")

LOG_FILE = os.path.join(LOG_DIR, "monitor.log")
OUTPUT_FILE = os.path.join(OUTPUT_DIR, "estado_servicios.txt")

# Crear directorios si no existen
os.makedirs(LOG_DIR, exist_ok=True)
os.makedirs(OUTPUT_DIR, exist_ok=True)

# ==============================
# SERVICIOS DISPONIBLES
# ==============================

SERVICIOS = {
    "1": "apache2",
    "2": "ssh",
    "3": "cron"
}

# ==============================
# FUNCIONES DE LOG
# ==============================

def escribir_log(mensaje):
    """Escribe mensajes en el fichero de log"""
    fecha = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    with open(LOG_FILE, "a") as f:
        f.write(f"[{fecha}] {mensaje}\n")

# ==============================
# FUNCIONES DE SERVICIOS
# ==============================

def estado_servicio(servicio):
    """Devuelve el estado de un servicio"""
    resultado = subprocess.run(
        ["systemctl", "is-active", servicio],
        capture_output=True,
        text=True
    )
    return resultado.stdout.strip()

def iniciar_servicio(servicio):
    subprocess.run(["systemctl", "start", servicio])
    escribir_log(f"Servicio {servicio} iniciado")

def parar_servicio(servicio):
    subprocess.run(["systemctl", "stop", servicio])
    escribir_log(f"Servicio {servicio} detenido")

def monitorizar_servicios(lista_servicios):
    """Monitoriza uno o varios servicios y guarda el estado"""
    with open(OUTPUT_FILE, "w") as f:
        for servicio in lista_servicios:
            estado = estado_servicio(servicio)
            linea = f"Servicio: {servicio} | Estado: {estado}"
            f.write(linea + "\n")
            escribir_log(linea)

# ==============================
# MENÚ MANUAL
# ==============================

def menu():
    print("\n--- MONITOR APACHE ---")
    print("1. Monitorizar servicios")
    print("2. Iniciar servicio")
    print("3. Parar servicio")
    print("4. Salir")

def seleccionar_servicios():
    print("\nServicios disponibles:")
    for k, v in SERVICIOS.items():
        print(f"{k}. {v}")

    opciones = input("Selecciona uno o varios (ej: 1,2): ")
    seleccionados = opciones.split(",")

    servicios = []
    for op in seleccionados:
        if op.strip() in SERVICIOS:
            servicios.append(SERVICIOS[op.strip()])

    return servicios

def modo_manual():
    while True:
        menu()
        opcion = input("Opción: ")

        if opcion == "1":
            servicios = seleccionar_servicios()
            monitorizar_servicios(servicios)

        elif opcion == "2":
            servicios = seleccionar_servicios()
            for s in servicios:
                iniciar_servicio(s)

        elif opcion == "3":
            servicios = seleccionar_servicios()
            for s in servicios:
                parar_servicio(s)

        elif opcion == "4":
            escribir_log("Salida del modo manual")
            print("Saliendo...")
            break
        else:
            print("Opción no válida")

# ==============================
# MODO AUTOMÁTICO (CRON)
# ==============================

def modo_automatico():
    """Modo pensado para cron"""
    servicios = ["apache2"]
    monitorizar_servicios(servicios)

# ==============================
# MAIN
# ==============================

if __name__ == "__main__":
    escribir_log("Script ejecutado")

    if len(sys.argv) > 1 and sys.argv[1] == "--auto":
        modo_automatico()
    else:
        modo_manual()
