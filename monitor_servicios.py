import requests
import datetime
import os
import sys

# --- Configuración Global ---

# Archivo de configuración que contendrá los servicios a monitorizar (uno por línea: Nombre,URL)
CONFIG_FILE = "servicios.conf"
# Directorio donde se guardarán los logs y el archivo de resultados
LOG_DIR = "monitor_logs"

# --- Funciones de Utilidad ---

def log_message(message, log_file):
    """Escribe un mensaje en el archivo de log."""
    timestamp = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    log_entry = f"[{timestamp}] {message}"
    
    # Imprimir también en consola
    print(log_entry) 
    
    try:
        # Crea el directorio de logs si no existe
        os.makedirs(LOG_DIR, exist_ok=True)
        log_path = os.path.join(LOG_DIR, log_file)
        with open(log_path, 'a', encoding='utf-8') as f:
            f.write(log_entry + '\n')
    except IOError as e:
        print(f"ERROR: No se pudo escribir en el archivo de log {log_path}. Razón: {e}")

def load_services():
    """Carga los servicios del archivo de configuración."""
    services = {}
    if not os.path.exists(CONFIG_FILE):
        log_message(f"ADVERTENCIA: Archivo de configuración '{CONFIG_FILE}' no encontrado.", "system.log")
        return services

    try:
        with open(CONFIG_FILE, 'r', encoding='utf-8') as f:
            for line in f:
                line = line.strip()
                if line and not line.startswith('#'):
                    # Espera formato: Nombre,URL
                    try:
                        name, url = line.split(',', 1)
                        services[name.strip()] = url.strip()
                    except ValueError:
                        log_message(f"ADVERTENCIA: Línea con formato incorrecto en {CONFIG_FILE}: '{line}'", "system.log")
        return services
    except IOError as e:
        log_message(f"ERROR: No se pudo leer el archivo '{CONFIG_FILE}'. Razón: {e}", "system.log")
        return {}

def check_service(name, url):
    """Verifica el estado de un servicio web."""
    try:
        # Intentar obtener la URL, con un timeout de 5 segundos
        response = requests.get(url, timeout=5)
        
        # Verificar si el código de estado es 2xx (éxito)
        if 200 <= response.status_code < 300:
            status = "UP"
            message = f"OK. Código de estado: {response.status_code}"
        else:
            status = "DOWN"
            message = f"ERROR. Código de estado: {response.status_code}"
            
    except requests.exceptions.RequestException as e:
        status = "DOWN"
        message = f"FALLO: Error de conexión o timeout. Razón: {e.__class__.__name__}"
        
    return name, url, status, message

def generate_report(results, mode):
    """Genera el archivo de resultados/reporte."""
    
    timestamp = datetime.datetime.now().strftime("%Y%m%d_%H%M%S")
    report_file = f"reporte_{mode}_{timestamp}.txt"
    report_path = os.path.join(LOG_DIR, report_file)
    
    log_message(f"Generando reporte en: {report_path}", "system.log")

    try:
        with open(report_path, 'w', encoding='utf-8') as f:
            f.write(f"--- Reporte de Monitorización ({mode.upper()}) ---\n")
            f.write(f"Fecha y Hora: {datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n")
            f.write("-" * 50 + "\n")
            f.write(f"| {'Nombre':<20} | {'URL':<40} | {'Estado':<6} | Mensaje\n")
            f.write("-" * 50 + "\n")
            
            for name, url, status, message in results:
                f.write(f"| {name:<20} | {url:<40} | {status:<6} | {message}\n")
                
            f.write("-" * 50 + "\n")
        
        log_message(f"Reporte generado exitosamente.", "system.log")

    except IOError as e:
        log_message(f"ERROR: No se pudo escribir el archivo de reporte {report_path}. Razón: {e}", "system.log")

# --- Lógica de Monitorización ---

def monitor_selected_services(services_to_check, mode):
    """Monitoriza un diccionario de servicios dados y genera el log y reporte."""
    
    log_file = f"{mode}_monitor.log"
    results = []
    
    log_message(f"Iniciando monitorización en modo {mode.upper()}...", log_file)
    
    # Iterar sobre los servicios
    for name, url in services_to_check.items():
        name, url, status, message = check_service(name, url)
        
        # Registrar el resultado en el log
        log_message(f"Servicio: {name} | URL: {url} | Estado: {status} | Mensaje: {message}", log_file)
        
        # Guardar el resultado para el reporte
        results.append((name, url, status, message))

    log_message("Monitorización finalizada.", log_file)
    
    # Generar el archivo de resultados (fichero)
    generate_report(results, mode)
    
# --- Modo Manual ---

def display_menu(services):
    """Muestra el menú interactivo."""
    
    print("\n" + "="*50)
    print("      Monitor de Servicios Web - MODO MANUAL")
    print("="*50)
    print("1. Monitorizar TODOS los servicios")
    
    # Listar servicios para monitorización individual
    for i, name in enumerate(services.keys(), 2):
        print(f"{i}. Monitorizar servicio: {name}")

    # Opciones de control (simuladas, ya que 'iniciar/parar' depende del SO/entorno)
    control_start = len(services) + 2
    control_stop = len(services) + 3
    print("-" * 50)
    print(f"{control_start}. SIMULAR INICIO de servicio (Solo registro)")
    print(f"{control_stop}. SIMULAR PARADA de servicio (Solo registro)")
    print("0. Salir")
    print("-" * 50)

def manual_mode():
    """Ejecuta el script en modo interactivo."""
    
    services = load_services()
    if not services:
        print("No hay servicios configurados. Saliendo del modo manual.")
        return

    while True:
        display_menu(services)
        
        try:
            choice = input("Seleccione una opción (0-{}): ".format(len(services) + 3)).strip()
            
            if choice == '0':
                print("Saliendo del modo manual. ¡Hasta pronto!")
                break
                
            choice_int = int(choice)
            service_names = list(services.keys())
            
            # Opción 1: Monitorizar todos
            if choice_int == 1:
                print("\n--- Monitorizando TODOS los servicios ---\n")
                monitor_selected_services(services, "manual_todos")
                
            # Opciones de servicios individuales (2 en adelante)
            elif 2 <= choice_int <= len(services) + 1:
                service_index = choice_int - 2
                name = service_names[service_index]
                url = services[name]
                
                print(f"\n--- Monitorizando servicio: {name} ---\n")
                monitor_selected_services({name: url}, f"manual_{name.lower().replace(' ', '_')}")
            
            # Opción SIMULAR INICIO
            elif choice_int == len(services) + 2:
                log_message("SIMULACIÓN: Se ha solicitado el INICIO de un servicio.", "system.log")
                print("\nSIMULACIÓN: Registrado el inicio. (La acción real debe implementarse aquí).\n")

            # Opción SIMULAR PARADA
            elif choice_int == len(services) + 3:
                log_message("SIMULACIÓN: Se ha solicitado la PARADA de un servicio.", "system.log")
                print("\nSIMULACIÓN: Registrada la parada. (La acción real debe implementarse aquí).\n")
                
            else:
                print(f"Opción no válida: {choice}. Intente de nuevo.")
                
        except ValueError:
            print("Entrada no válida. Por favor, ingrese un número.")
        except KeyboardInterrupt:
            print("\nSaliendo del modo manual. ¡Hasta pronto!")
            break

# --- Modo Automatizado (Cron) ---

def automated_mode():
    """Ejecuta la monitorización para todos los servicios de forma no interactiva."""
    
    log_message("Iniciando modo automatizado (CRON)...", "system.log")
    
    services = load_services()
    
    if not services:
        log_message("ERROR: No se pudo cargar ningún servicio para el modo automatizado. Abortando.", "automated_monitor.log")
        return

    monitor_selected_services(services, "automatizado")
    
    log_message("Modo automatizado (CRON) finalizado.", "system.log")

# --- Lógica Principal de Ejecución ---

def main():
    """Determina el modo de ejecución basado en los argumentos de la línea de comandos."""
    
    # 1. Crear el archivo de configuración si no existe (con ejemplos)
    if not os.path.exists(CONFIG_FILE):
        log_message(f"Creando archivo de configuración de ejemplo: '{CONFIG_FILE}'", "system.log")
        with open(CONFIG_FILE, 'w', encoding='utf-8') as f:
            f.write("# Formato: Nombre,URL\n")
            f.write("Google,https://www.google.com\n")
            f.write("Servicio Local A,http://localhost:8080/health\n")
            f.write("Sitio Inexistente,http://sitio.definitivamente.noexiste.com\n")
            
    # 2. Determinar el modo de ejecución
    if len(sys.argv) > 1 and sys.argv[1].lower() == 'auto':
        # Ejecutar en modo automatizado (útil para cron)
        automated_mode()
    else:
        # Ejecutar en modo manual por defecto
        manual_mode()

if __name__ == "__main__":
    main()