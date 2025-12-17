#!/bin/bash
# ------------------------------------------------------------
# Script: monitor_servicios.sh
# Proyecto ASO - Monitorización de Servicios Web
# Apache + Nginx
# Incluye catalogación de errores y mensajes informativos
# ------------------------------------------------------------

set -o pipefail

# =========================
# VARIABLES
# =========================
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
DATE_ISO="$(date '+%Y-%m-%dT%H:%M:%S%z')"
HOSTNAME="$(hostname)"
IP_LOCAL="$(hostname -I | awk '{print $1}')"

LOG_DIR="$SCRIPT_DIR/logs"
LOG_FILE="$LOG_DIR/monitor.csv"

SERVICIOS_WEB=("apache2" "nginx")
PUERTOS=("80" "443")

mkdir -p "$LOG_DIR"

# Cabecera CSV
if [[ ! -f "$LOG_FILE" ]]; then
  echo "fecha,host,ip,servicio,estado_servicio,puerto,http_code,http_time,cpu_load,ram_pct,disco_pct,resultado,descripcion" > "$LOG_FILE"
fi

# =========================
# FUNCIONES
# =========================

log_csv() {
  echo "$1" >> "$LOG_FILE"
}

# Función para catalogar errores con texto
catalogar_error() {
  local estado=$1
  local http_code=$2
  local ram=$3
  local disco=$4

  if [[ "$estado" -ne 0 ]]; then
    echo "CRIT: Servicio detenido"
  elif [[ "$http_code" -ge 500 ]]; then
    echo "CRIT: Error servidor web HTTP $http_code"
  elif [[ "$disco" -ge 90 ]]; then
    echo "CRIT: Disco casi lleno ($disco%)"
  elif [[ "$http_code" -ge 400 ]]; then
    echo "WARN: HTTP $http_code"
  elif [[ "$ram" -ge 80 ]]; then
    echo "WARN: RAM alta ($ram%)"
  elif [[ "$disco" -ge 80 ]]; then
    echo "WARN: Disco alto ($disco%)"
  else
    echo "OK: Todo correcto"
  fi
}

check_servicio() {
  systemctl is-active --quiet "$1"
  echo $?
}

check_reinicios() {
  journalctl -u "$1" --since "1 hour ago" | grep -i "started" | wc -l
}

check_puerto() {
  for p in "${PUERTOS[@]}"; do
    ss -lnt | grep -q ":$p "
    [[ $? -eq 0 ]] && echo "$p" && return
  done
  echo "NO"
}

check_http() {
  curl -s -o /dev/null -w "%{http_code} %{time_total}" http://localhost
}

check_cpu() {
  uptime | awk -F'load average:' '{print $2}' | cut -d',' -f1 | xargs
}

check_ram() {
  free | awk '/Mem:/ { printf "%.0f", $3/$2 * 100 }'
}

check_disco() {
  df / | awk 'NR==2 { gsub("%",""); print $5 }'
}

calcular_resultado() {
  local estado=$1
  local http_code=$2
  local ram=$3
  local disco=$4

  if [[ "$estado" -ne 0 || "$http_code" -ge 500 || "$disco" -ge 90 ]]; then
    echo "CRIT"
  elif [[ "$http_code" -ge 400 || "$ram" -ge 80 || "$disco" -ge 80 ]]; then
    echo "WARN"
  else
    echo "OK"
  fi
}

# =========================
# CHEQUEO COMPLETO
# =========================
ejecutar_chequeo() {

  cpu_load=$(check_cpu)
  ram_pct=$(check_ram)
  disco_pct=$(check_disco)

  RESULTADO_GLOBAL="OK"

  for servicio in "${SERVICIOS_WEB[@]}"; do

    estado_servicio=$(check_servicio "$servicio")
    reinicios=$(check_reinicios "$servicio")
    puerto=$(check_puerto)

    read http_code http_time <<< "$(check_http)"

    resultado=$(calcular_resultado "$estado_servicio" "$http_code" "$ram_pct" "$disco_pct")
    descripcion=$(catalogar_error "$estado_servicio" "$http_code" "$ram_pct" "$disco_pct")

    # Escalar resultado global
    [[ "$resultado" == "CRIT" ]] && RESULTADO_GLOBAL="CRIT"
    [[ "$resultado" == "WARN" && "$RESULTADO_GLOBAL" == "OK" ]] && RESULTADO_GLOBAL="WARN"

    log_csv "$DATE_ISO,$HOSTNAME,$IP_LOCAL,$servicio,$estado_servicio,$puerto,$http_code,$http_time,$cpu_load,$ram_pct,$disco_pct,$resultado,\"$descripcion\""

    echo "Servicio: $servicio → $resultado"
    echo "Descripción: $descripcion"
    echo "Reinicios última hora: $reinicios"
    echo "-----------------------------"
  done

  echo ""
  echo "Resultado GLOBAL de la ejecución: $RESULTADO_GLOBAL"
  echo ""
}

# =========================
# FUNCIONES DE INICIO/PARO
# =========================
iniciar_servicio() {
  local svc=$1
  systemctl start "$svc"
  sleep 1
  echo "Servicio $svc INICIADO ✅"
}

parar_servicio() {
  local svc=$1
  systemctl stop "$svc"
  sleep 1
  echo "Servicio $svc PARADO 🛑"
}

# =========================
# MENÚ MANUAL
# =========================
menu_manual() {
  while true; do
    clear
    echo "===== MONITORIZACIÓN ASO ====="
    echo "1) Monitorizar servicios web"
    echo "2) Iniciar Apache"
    echo "3) Parar Apache"
    echo "4) Iniciar Nginx"
    echo "5) Parar Nginx"
    echo "6) Ver log"
    echo "7) Salir"
    echo ""
    read -p "Opción: " opt

    case $opt in
      1) ejecutar_chequeo; read -p "ENTER para continuar" ;;
      2) iniciar_servicio "apache2"; read -p "ENTER para continuar" ;;
      3) parar_servicio "apache2"; read -p "ENTER para continuar" ;;
      4) iniciar_servicio "nginx"; read -p "ENTER para continuar" ;;
      5) parar_servicio "nginx"; read -p "ENTER para continuar" ;;
      6) less "$LOG_FILE" ;;
      7) exit 0 ;;
      *) echo "Opción no válida"; sleep 1 ;;
    esac
  done
}

# =========================
# MAIN
# =========================
if [[ "$1" == "auto" ]]; then
  ejecutar_chequeo
else
  menu_manual
fi
