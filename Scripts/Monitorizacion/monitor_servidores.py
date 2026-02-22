#!/usr/bin/env python3
# -*- coding: utf-8 -*-

"""
Monitor de servidores remotos y local vía SSH
Utiliza alias definidos en ~/.ssh/config (ej: web, web2)

Requiere:
- Conexión sin contraseña (clave pública configurada)
- nc, curl, free, df, awk, uptime instalados en los servidores
"""

import subprocess
import datetime
import os
import sys
import re


# ================= CONFIGURACIÓN =================

PUERTO_WEB = 80

# Alias tal como están en ~/.ssh/config
# None = ejecución local (sin SSH)
HOSTS = {
    "local": None,
    "web":   "web",
    "web2":  "web2"
}

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
LOG_DIR  = os.path.join(BASE_DIR, "logs")
os.makedirs(LOG_DIR, exist_ok=True)

# Generar nombre de log con formato legible: monitor_YYYY-MM-DD_HH-MM-SS.log
timestamp = datetime.datetime.now().strftime("%Y-%m-%d_%H-%M-%S")
LOG_FILE = os.path.join(LOG_DIR, f"monitor_{timestamp}.log")


# ================= FUNCIONES DE LOG =================

def quitar_colores(texto):
    """Elimina códigos ANSI de color para que el log quede limpio"""
    ansi_escape = re.compile(r'\x1B(?:[@-Z\\-_]|\[[0-?]*[ -/]*[@-~])')
    return ansi_escape.sub('', texto)


def log(mensaje):
    fecha = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    mensaje_limpio = quitar_colores(mensaje)
    with open(LOG_FILE, "a", encoding="utf-8") as f:
        f.write(f"[{fecha}] {mensaje_limpio}\n")


def mostrar(mensaje):
    print(mensaje)          # Con colores en terminal
    log(mensaje)            # Sin colores en archivo


# ================= EJECUCIÓN DE COMANDOS =================

def ejecutar_comando(comando, host_alias, mostrar_error=True):
    try:
        if host_alias:
            cmd_final = ["ssh", "-o", "BatchMode=yes", "-o", "ConnectTimeout=10", host_alias, comando]
            shell = False
        else:
            cmd_final = comando
            shell = True

        resultado = subprocess.run(
            cmd_final,
            shell=shell,
            stdout=subprocess.PIPE,
            stderr=subprocess.PIPE,
            text=True,
            timeout=15,
            encoding="utf-8"
        )

        if resultado.returncode != 0:
            error_msg = resultado.stderr.strip() or "Error desconocido"
            if "Could not resolve hostname" in error_msg:
                error_msg = "No se pudo resolver el hostname (revisa ~/.ssh/config)"
            elif "Connection timed out" in error_msg or "No route to host" in error_msg:
                error_msg = "No se pudo conectar al servidor (timeout o no accesible)"
            elif mostrar_error:
                log(f"ERROR ejecutando '{comando}' en {host_alias or 'local'}: {error_msg}")
            
            return "", resultado.returncode, False, error_msg

        return resultado.stdout.strip(), resultado.returncode, True, ""

    except subprocess.TimeoutExpired:
        return "", 1, False, "Tiempo de espera agotado"
    except Exception as e:
        return "", 1, False, str(e)


# ================= COMPROBACIONES =================

def esta_puerto_abierto(host_alias, nombre):
    _, _, ok, error = ejecutar_comando(f"nc -z 127.0.0.1 {PUERTO_WEB}", host_alias, mostrar_error=False)
    if not ok and error:
        mostrar(f"[{nombre}] No se pudo comprobar puerto → {error}")
        return False
    estado = "sí" if ok else "no"
    mostrar(f"[{nombre}] Puerto {PUERTO_WEB} escuchando: {estado}")
    return ok


def obtener_respuesta_http(host_alias, nombre):
    cmd = f"curl -s -o /dev/null -w '%{{http_code}}' --max-time 8 http://127.0.0.1/"
    salida, _, ok, error = ejecutar_comando(cmd, host_alias, mostrar_error=False)
    
    if not ok:
        if error:
            mostrar(f"[{nombre}] No se pudo obtener respuesta HTTP → {error}")
        return "ERROR"
    
    if not salida.isdigit():
        return "ERROR"
    
    codigo = salida
    mostrar(f"[{nombre}] Respuesta HTTP: {codigo}")
    return codigo


def estado_servicio_web(host_alias, nombre):
    puerto_ok = esta_puerto_abierto(host_alias, nombre)
    http_code = obtener_respuesta_http(host_alias, nombre)
    
    if not puerto_ok:
        estado = "inactive (puerto cerrado)"
    elif http_code == "200":
        estado = "active"
    else:
        estado = f"inactive (HTTP {http_code})"
    
    mostrar(f"[{nombre}] Estado servicio web: {estado}")
    return estado == "active"


def obtener_rendimiento(host_alias, nombre):
    mostrar(f"\n[{nombre}] --- Rendimiento del equipo ---")

    # Carga CPU
    salida, _, _, error = ejecutar_comando("uptime", host_alias)
    load = "N/A"
    if salida and "load average:" in salida:
        try:
            load = salida.split("load average:")[1].split(",")[0].strip()
        except:
            pass
    mostrar(f"  Carga CPU (1 min): {load}")

    # RAM
    salida, _, _, _ = ejecutar_comando("free -m", host_alias)
    uso_ram = "N/A"
    for linea in salida.splitlines():
        if linea.startswith("Mem:"):
            campos = linea.split()
            if len(campos) >= 7:
                try:
                    total = int(campos[1])
                    usada = int(campos[2]) + int(campos[3])  # used + buffers/cache approx
                    if total > 0:
                        uso_ram = round((usada / total) * 100, 1)
                except:
                    pass
    mostrar(f"  Uso RAM: {uso_ram}%")

    # Disco /
    salida, _, _, _ = ejecutar_comando("df -h / | tail -1 | awk '{print $5}'", host_alias)
    disco = salida.rstrip('%') if salida else "N/A"
    mostrar(f"  Uso disco /: {disco}%")


# ================= MONITORIZACIÓN COMPLETA =================

def monitor_completo(nombre, host_alias):
    print("\n" + "═" * 70)
    mostrar(f" MONITOR COMPLETO → {nombre.upper()} ({host_alias or 'LOCAL'})")
    print("─" * 70)

    activo = estado_servicio_web(host_alias, nombre)
    obtener_rendimiento(host_alias, nombre)

    resultado = "OK" if activo else "CRÍTICO"
    color = "\033[92m" if activo else "\033[91m"
    reset = "\033[0m"

    # En pantalla con color
    print()
    print(f"RESULTADO FINAL: {color}{resultado}{reset}")

    # En log sin color + formato más claro
    log(f"RESULTADO FINAL: {resultado}")

    print("═" * 70 + "\n")


# ================= MENÚS =================

def menu_principal():
    print("""
=== MONITOR CENTRAL ===
1. Seleccionar máquina
2. Monitorizar TODAS
3. Salir
""")


def menu_maquina(nombre):
    print(f"""
--- {nombre.upper()} ---
1. Rendimiento del equipo
2. Estado Nginx (puerto + HTTP)
3. Comprobar puerto {PUERTO_WEB}
4. Comprobar respuesta HTTP
5. Monitorización completa
6. Volver
""")


def seleccionar_maquina():
    nombres = list(HOSTS.keys())
    for i, n in enumerate(nombres, 1):
        print(f"{i}. {n}")
    
    while True:
        try:
            sel = int(input("\nSelecciona número: "))
            if 1 <= sel <= len(nombres):
                return nombres[sel-1]
            print(f"Elige entre 1 y {len(nombres)}")
        except ValueError:
            print("Introduce un número válido")


# ================= MODO INTERACTIVO =================

def modo_manual():
    while True:
        menu_principal()
        opcion = input("Opción: ").strip()

        if opcion == "1":
            nombre = seleccionar_maquina()
            host = HOSTS[nombre]

            while True:
                menu_maquina(nombre)
                sub_op = input("Opción: ").strip()

                if sub_op == "1":
                    obtener_rendimiento(host, nombre)
                elif sub_op == "2":
                    estado_servicio_web(host, nombre)
                elif sub_op == "3":
                    esta_puerto_abierto(host, nombre)
                elif sub_op == "4":
                    obtener_respuesta_http(host, nombre)
                elif sub_op == "5":
                    monitor_completo(nombre, host)
                elif sub_op == "6":
                    break
                else:
                    print("Opción no válida")

                print()  # línea en blanco para separar

        elif opcion == "2":
            print("\nMonitorizando todas las máquinas...\n")
            for nom, h in HOSTS.items():
                monitor_completo(nom, h)
            input("\nPulse Enter para continuar...")

        elif opcion == "3":
            print("Saliendo...")
            break

        else:
            print("Opción no válida")


# ================= INICIO =================

if __name__ == "__main__":
    print("Iniciando monitor de servidores...")
    log("Script iniciado")
    try:
        modo_manual()
    except KeyboardInterrupt:
        print("\n\nInterrumpido por el usuario.")
    except Exception as e:
        print(f"\nError inesperado: {e}")
        log(f"ERROR CRÍTICO: {str(e)}")
    finally:
        log("Script finalizado")