<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cierre y Arqueo Diario - Ares Gym</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,900&display=swap" rel="stylesheet">
<style>
  @page {
    size: A4 portrait;
    margin: 6mm 8mm;
  }

  :root {
    --black: #0c0d0e;
    --dark: #141416;
    --wine: #8b1124;
    --wine-dark: #6e1423;
    --wine-bright: #c51d34;
    --gray-bg: #f8fafc;
    --gray-card: #f1f5f9;
    --gray-border: #e2e8f0;
    --gray-muted: #64748b;
    --gray-dark: #334155;
    --green-bg: #ecfdf5;
    --green-text: #059669;
    --green-border: #a7f3d0;
  }

  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: #e2e8f0;
    color: #0f172a;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
    padding: 20px 0;
  }

  .sheet {
    max-width: 210mm;
    margin: 0 auto;
    background: #ffffff;
    position: relative;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
  }

  /* ============ TOOLBAR ============ */
  .toolbar {
    max-width: 210mm;
    margin: 0 auto 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 4px;
    gap: 10px;
    flex-wrap: wrap;
  }
  .toolbar-left, .toolbar-right {
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .toolbar button, .toolbar a, .toolbar input {
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    border-radius: 6px;
    padding: 8px 14px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    cursor: pointer;
  }
  .toolbar .btn-primary {
    background: var(--wine);
    color: #ffffff;
    border: 1px solid var(--wine-bright);
    transition: all 0.2s ease;
  }
  .toolbar .btn-primary:hover {
    background: var(--wine-bright);
  }
  .toolbar .btn-secondary {
    background: #ffffff;
    color: var(--black);
    border: 1px solid #cbd5e1;
  }
  .toolbar .btn-secondary:hover {
    background: #f1f5f9;
  }

  /* ============ HEADER ============ */
  .header {
    background: var(--dark);
    color: #ffffff;
    padding: 18px 24px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 3px solid var(--wine);
    position: relative;
  }
  .header .brand-title {
    font-size: 26px;
    font-weight: 900;
    letter-spacing: 0.5px;
    line-height: 1;
    text-transform: uppercase;
  }
  .header .brand-title span {
    color: var(--wine-bright);
  }
  .header .brand-subtitle {
    font-size: 8.5px;
    letter-spacing: 3.5px;
    text-transform: uppercase;
    color: #a1a1aa;
    margin-top: 5px;
    font-weight: 600;
  }
  .header .doc-meta {
    text-align: right;
  }
  .header .doc-tag {
    font-size: 9px;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: var(--wine-bright);
    font-weight: 800;
    margin-bottom: 2px;
  }
  .header .doc-title {
    font-size: 19px;
    font-weight: 900;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: #ffffff;
  }
  .header .doc-folio {
    font-size: 10.5px;
    color: #94a3b8;
    margin-top: 2px;
    font-weight: 500;
  }

  /* ============ 4-COLUMN META BAR ============ */
  .meta-bar {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    background: var(--gray-bg);
    border-bottom: 1px solid var(--gray-border);
  }
  .meta-cell {
    padding: 9px 16px;
    border-right: 1px solid var(--gray-border);
  }
  .meta-cell:last-child {
    border-right: none;
  }
  .meta-cell .label {
    font-size: 8px;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--gray-muted);
    font-weight: 700;
    margin-bottom: 2px;
  }
  .meta-cell .value {
    font-size: 12.5px;
    font-weight: 800;
    color: var(--black);
  }
  .meta-cell .value.highlight {
    color: var(--wine);
    letter-spacing: 0.5px;
  }

  /* ============ MAIN CONTENT ============ */
  .content {
    padding: 16px 22px;
  }

  /* ============ SECTION TITLE ============ */
  .section-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    margin-top: 4px;
  }
  .section-badge {
    background: var(--wine);
    color: #ffffff;
    font-size: 10px;
    font-weight: 900;
    width: 18px;
    height: 18px;
    border-radius: 3px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }
  .section-title {
    font-size: 11px;
    letter-spacing: 1px;
    text-transform: uppercase;
    font-weight: 800;
    color: var(--black);
  }

  /* ============ TABLES ============ */
  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 10px;
  }
  th {
    background: var(--dark);
    color: #ffffff;
    text-transform: uppercase;
    font-size: 8.5px;
    letter-spacing: 0.8px;
    font-weight: 700;
    padding: 5px 8px;
    text-align: left;
  }
  th.text-center, td.text-center { text-align: center; }
  th.text-right, td.text-right { text-align: right; }
  td {
    padding: 5px 8px;
    border-bottom: 1px solid var(--gray-border);
    color: #1e293b;
    vertical-align: middle;
  }
  tr:nth-child(even) td {
    background: #fcfcfd;
  }
  .num {
    text-align: right;
    font-variant-numeric: tabular-nums;
  }

  /* ============ TWO-COLUMN GRID ============ */
  .grid-2 {
    display: grid;
    grid-template-columns: 46% 52%;
    gap: 2%;
    margin-bottom: 14px;
  }
  .col {
    display: flex;
    flex-direction: column;
  }

  /* Table Specific Styles */
  .table-summary-row td {
    border-top: 1.5px solid var(--black);
    font-weight: 800;
    background: var(--gray-card) !important;
    padding: 6px 8px;
  }
  .table-summary-row td.text-wine {
    color: var(--wine);
    font-size: 11px;
  }

  /* ============ SECTION 3: RECONCILIATION TABLE ============ */
  .reconciliation-table {
    margin-bottom: 8px;
  }
  .badge-ok {
    display: inline-block;
    background: var(--green-bg);
    color: var(--green-text);
    border: 1px solid var(--green-border);
    padding: 1px 8px;
    border-radius: 4px;
    font-size: 8.5px;
    font-weight: 800;
    text-align: center;
    letter-spacing: 0.5px;
  }
  .badge-status-cuadrado {
    display: inline-block;
    background: var(--green-bg);
    color: var(--green-text);
    border: 1px solid var(--green-border);
    padding: 2px 10px;
    border-radius: 4px;
    font-size: 9px;
    font-weight: 900;
    letter-spacing: 0.5px;
  }
  .badge-status-faltante {
    display: inline-block;
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fca5a5;
    padding: 2px 10px;
    border-radius: 4px;
    font-size: 9px;
    font-weight: 900;
    letter-spacing: 0.5px;
  }
  .badge-status-sobrante {
    display: inline-block;
    background: #fefce8;
    color: #d97706;
    border: 1px solid #fde047;
    padding: 2px 10px;
    border-radius: 4px;
    font-size: 9px;
    font-weight: 900;
    letter-spacing: 0.5px;
  }

  /* ============ DARK SUMMARY BOX ============ */
  .summary-banner {
    background: var(--dark);
    color: #ffffff;
    border-radius: 4px;
    padding: 12px 16px;
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    text-align: center;
    margin-bottom: 14px;
    border-left: 4px solid var(--wine);
  }
  .summary-item + .summary-item {
    border-left: 1px solid rgba(255, 255, 255, 0.12);
  }
  .summary-label {
    font-size: 8px;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #94a3b8;
    margin-bottom: 4px;
    font-weight: 700;
  }
  .summary-value {
    font-size: 16px;
    font-weight: 900;
    color: #ffffff;
  }
  .summary-value.green {
    color: #4ade80;
  }
  .summary-value.red {
    color: #f87171;
  }
  .summary-value.yellow {
    color: #facc15;
  }

  /* ============ OBSERVATIONS ============ */
  .obs-container {
    margin-bottom: 22px;
  }
  .obs-box {
    border: 1px solid var(--gray-border);
    border-radius: 4px;
    padding: 8px 12px;
    font-size: 9.5px;
    color: var(--gray-dark);
    background: var(--gray-bg);
    line-height: 1.45;
  }

  /* ============ SIGNATURES ============ */
  .signatures {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
    padding: 10px 40px 18px;
  }
  .sig-block {
    text-align: center;
  }
  .sig-line {
    border-top: 1px solid #1e293b;
    padding-top: 6px;
    font-size: 10px;
    font-weight: 800;
    color: var(--black);
  }
  .sig-subtitle {
    font-size: 8px;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--gray-muted);
    margin-top: 2px;
    font-weight: 600;
  }

  /* ============ FOOTER STRIP ============ */
  .footer-strip {
    background: var(--wine-dark);
    color: #ffffff;
    text-align: center;
    font-size: 8px;
    letter-spacing: 2px;
    text-transform: uppercase;
    padding: 6px;
    font-weight: 700;
  }

  @media print {
    .toolbar { display: none !important; }
    body { background: #ffffff; padding: 0; }
    .sheet { box-shadow: none; max-width: 100%; margin: 0; }
  }
</style>
</head>
<body>

<!-- TOOLBAR (Sólo para vista en pantalla) -->
<div class="toolbar">
  <div class="toolbar-left">
    <button onclick="window.print()" class="btn-primary">🖨️ Descargar PDF / Imprimir</button>
    <a href="{{ route('pagos') }}" class="btn-secondary">← Volver al Control de Caja</a>
  </div>
  <div class="toolbar-right" style="font-size: 11px; font-weight: 700; color: #475569; display: flex; align-items: center; gap: 8px;">
    <span style="background: #f1f5f9; border: 1px solid #cbd5e1; padding: 4px 10px; border-radius: 6px;">🔒 Documento Oficial No Editable</span>
    <span style="background: #f1f5f9; border: 1px solid #cbd5e1; padding: 4px 10px; border-radius: 6px;">⏰ Horario: 05:00 – 22:00</span>
  </div>
</div>

<!-- DOCUMENTO OFICIAL A4 -->
<div class="sheet">

  <!-- ===================== HEADER ===================== -->
  <div class="header">
    <div>
      <div class="brand-title">ARES <span>GYM</span></div>
      <div class="brand-subtitle">FUERZA &middot; DISCIPLINA &middot; HONOR</div>
    </div>
    <div class="doc-meta">
      <div class="doc-tag">SISTEMA DE MEMBRESÍAS</div>
      <div class="doc-title">CIERRE Y ARQUEO DIARIO</div>
      <div class="doc-folio">{{ $folio }}</div>
    </div>
  </div>

  <!-- ===================== 4-COLUMN META BAR ===================== -->
  <div class="meta-bar">
    <div class="meta-cell">
      <div class="label">FECHA DE OPERACIÓN</div>
      <div class="value">{{ $fecha }}</div>
    </div>
    <div class="meta-cell">
      <div class="label">ADMINISTRADOR / CAJERO</div>
      <div class="value">{{ $cajero }}</div>
    </div>
    <div class="meta-cell">
      <div class="label">HORARIO DE JORNADA</div>
      <div class="value">{{ $horarioJornada }}</div>
    </div>
    <div class="meta-cell">
      <div class="label">ESTADO DEL CIERRE</div>
      <div class="value highlight">{{ $estadoCierre }}</div>
    </div>
  </div>

  <!-- ===================== CUERPO PRINCIPAL ===================== -->
  <div class="content">

    <!-- SECCIONES 1 Y 2 (DOS COLUMNAS) -->
    <div class="grid-2">

      <!-- COLUMNA 1: CONTEO FÍSICO (EFECTIVO) -->
      <div class="col">
        <div class="section-header">
          <div class="section-badge">1</div>
          <div class="section-title">CONTEO FÍSICO (EFECTIVO)</div>
        </div>

        <table>
          <thead>
            <tr>
              <th style="width: 52%;">DENOMINACIÓN</th>
              <th class="text-center" style="width: 18%;">CANT.</th>
              <th class="text-right" style="width: 30%;">SUBTOTAL</th>
            </tr>
          </thead>
          <tbody>
            @if($b100 > 0)
            <tr>
              <td>Billete $100.00 (Especial)</td>
              <td class="text-center">{{ $b100 }}</td>
              <td class="num">$ {{ number_format($sb100, 2) }}</td>
            </tr>
            @endif
            @if($b50 > 0)
            <tr>
              <td>Billete $50.00 (Especial)</td>
              <td class="text-center">{{ $b50 }}</td>
              <td class="num">$ {{ number_format($sb50, 2) }}</td>
            </tr>
            @endif
            <tr>
              <td>Billete $20.00</td>
              <td class="text-center">{{ $b20 }}</td>
              <td class="num">$ {{ number_format($sb20, 2) }}</td>
            </tr>
            <tr>
              <td>Billete $10.00</td>
              <td class="text-center">{{ $b10 }}</td>
              <td class="num">$ {{ number_format($sb10, 2) }}</td>
            </tr>
            <tr>
              <td>Billete $5.00</td>
              <td class="text-center">{{ $b5 }}</td>
              <td class="num">$ {{ number_format($sb5, 2) }}</td>
            </tr>
            <tr>
              <td>Billete $1.00</td>
              <td class="text-center">{{ $b1 }}</td>
              <td class="num">$ {{ number_format($sb1, 2) }}</td>
            </tr>
            <tr>
              <td>Monedas Fraccionarias</td>
              <td class="text-center">--</td>
              <td class="num">$ {{ number_format($smon, 2) }}</td>
            </tr>
            <tr class="table-summary-row">
              <td colspan="2">TOTAL EFECTIVO EN CAJA</td>
              <td class="num text-wine">$ {{ number_format($totalEfectivoCaja, 2) }}</td>
            </tr>
            <tr>
              <td colspan="2" style="color: #64748b; font-size: 9.5px;">(-) Fondo Base Inicial (Cambio)</td>
              <td class="num" style="color: #64748b; font-size: 9.5px;">- $ {{ number_format($fondoBase, 2) }}</td>
            </tr>
            <tr class="table-summary-row">
              <td colspan="2">(=) TOTAL RECAUDO EFECTIVO</td>
              <td class="num" style="font-weight: 800;">$ {{ number_format($totalRecaudoEfectivo, 2) }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- COLUMNA 2: TRANSFERENCIAS BANCARIAS -->
      <div class="col">
        <div class="section-header">
          <div class="section-badge">2</div>
          <div class="section-title">TRANSFERENCIAS BANCARIAS</div>
        </div>

        <table>
          <thead>
            <tr>
              <th style="width: 26%;">BANCO / APP</th>
              <th style="width: 24%;">N.&deg; REF.</th>
              <th style="width: 32%;">CLIENTE / PLAN</th>
              <th class="text-right" style="width: 18%;">MONTO</th>
            </tr>
          </thead>
          <tbody>
            @forelse($transfersList as $t)
              <tr>
                <td style="font-weight: 600;">{{ $t['bank'] }}</td>
                <td style="font-family: monospace; color: #475569; font-size: 9px;">{{ $t['ref'] }}</td>
                <td>
                  <div style="font-weight: 600; line-height: 1.1;">{{ $t['client'] }}</div>
                  <div style="font-size: 8px; color: #64748b;">({{ $t['plan_abbr'] }})</div>
                </td>
                <td class="num" style="font-weight: 700;">$ {{ number_format($t['amount'], 2) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center" style="color: #94a3b8; padding: 18px 0; font-size: 9px;">
                  No se registraron cobros por transferencia en esta fecha.
                </td>
              </tr>
            @endforelse

            <tr class="table-summary-row">
              <td colspan="3">TOTAL TRANSFERENCIAS ({{ $txTransferencia }} refs)</td>
              <td class="num text-wine">$ {{ number_format($montoTransferencia, 2) }}</td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>

    <!-- SECCIÓN 3: CONCILIACIÓN Y BALANCE GENERAL -->
    <div style="margin-bottom: 14px;">
      <div class="section-header">
        <div class="section-badge">3</div>
        <div class="section-title">CONCILIACIÓN Y BALANCE GENERAL (SISTEMA VS REAL)</div>
      </div>

      <table class="reconciliation-table">
        <thead>
          <tr>
            <th style="width: 36%;">CANAL / CONCEPTO</th>
            <th class="text-right" style="width: 20%;">ESPERADO (SISTEMA)</th>
            <th class="text-right" style="width: 20%;">REAL / DECLARADO</th>
            <th class="text-right" style="width: 14%;">DIFERENCIA</th>
            <th class="text-center" style="width: 10%;">ESTADO</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="font-weight: 600;">Fondo Base Inicial (Caja Chica)</td>
            <td class="num">$ {{ number_format($fondoBase, 2) }}</td>
            <td class="num">$ {{ number_format($fondoBase, 2) }}</td>
            <td class="num">$ 0.00</td>
            <td class="text-center"><span class="badge-ok">OK</span></td>
          </tr>
          <tr>
            <td style="font-weight: 600;">Membres&iacute;as cobradas en Efectivo</td>
            <td class="num">$ {{ number_format($montoEfectivo, 2) }}</td>
            <td class="num">$ {{ number_format($totalRecaudoEfectivo, 2) }}</td>
            <td class="num font-bold {{ $diferenciaFinal == 0 ? '' : ($diferenciaFinal < 0 ? 'text-red' : 'text-yellow') }}">{{ $diferenciaFinal > 0 ? '+' : '' }}$ {{ number_format($diferenciaFinal, 2) }}</td>
            <td class="text-center">
              @if($diferenciaFinal == 0)
                <span class="badge-ok">OK</span>
              @elseif($diferenciaFinal < 0)
                <span class="badge-status-faltante">FALTANTE</span>
              @else
                <span class="badge-status-sobrante">SOBRANTE</span>
              @endif
            </td>
          </tr>
          <tr>
            <td style="font-weight: 600;">Membres&iacute;as cobradas por Transferencia</td>
            <td class="num">$ {{ number_format($montoTransferencia, 2) }}</td>
            <td class="num">$ {{ number_format($montoTransferencia, 2) }}</td>
            <td class="num">$ 0.00</td>
            <td class="text-center"><span class="badge-ok">OK</span></td>
          </tr>
          <tr class="table-summary-row">
            <td>TOTAL VENTAS DEL D&Iacute;A (MEMBRES&Iacute;AS)</td>
            <td class="num">$ {{ number_format($totalVentas, 2) }}</td>
            <td class="num">$ {{ number_format($totalRecaudoEfectivo + $montoTransferencia, 2) }}</td>
            <td class="num font-bold {{ $diferenciaFinal == 0 ? '' : ($diferenciaFinal < 0 ? 'text-red' : 'text-yellow') }}">{{ $diferenciaFinal > 0 ? '+' : '' }}$ {{ number_format($diferenciaFinal, 2) }}</td>
            <td class="text-center">
              @if($diferenciaFinal == 0)
                <span class="badge-status-cuadrado">CUADRADO</span>
              @elseif($diferenciaFinal < 0)
                <span class="badge-status-faltante">FALTANTE</span>
              @else
                <span class="badge-status-sobrante">SOBRANTE</span>
              @endif
            </td>
          </tr>
        </tbody>
      </table>

      <!-- TARJETA OSCURA DE 3 BLOQUES -->
      <div class="summary-banner">
        <div class="summary-item">
          <div class="summary-label">TOTAL SISTEMA (VENTAS + BASE)</div>
          <div class="summary-value">$ {{ number_format($totalSistema, 2) }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">TOTAL F&Iacute;SICO + BANCOS</div>
          <div class="summary-value">$ {{ number_format($totalFisicoBancos, 2) }}</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">DIFERENCIA FINAL</div>
          <div class="summary-value {{ $diferenciaFinal == 0 ? 'green' : ($diferenciaFinal < 0 ? 'red' : 'yellow') }}">
            {{ $diferenciaFinal > 0 ? '+' : '' }}$ {{ number_format($diferenciaFinal, 2) }}
          </div>
        </div>
      </div>
    </div>

    <!-- SECCIÓN 4: OBSERVACIONES / NOTAS DEL DÍA -->
    <div class="obs-container">
      <div class="section-header">
        <div class="section-badge">4</div>
        <div class="section-title">OBSERVACIONES / NOTAS DEL D&Iacute;A</div>
      </div>
      <div class="obs-box">
        {{ $observaciones }}
      </div>
    </div>

  </div>

  <!-- ===================== FIRMAS ===================== -->
  <div class="signatures">
    <div class="sig-block">
      <div class="sig-line">Firma Administrador / Responsable</div>
      <div class="sig-subtitle">REVISI&Oacute;N DE CAJA Y BANCOS</div>
    </div>
    <div class="sig-block">
      <div class="sig-line">Firma Auditor&iacute;a / Control</div>
      <div class="sig-subtitle">VERIFICACI&Oacute;N DE CIERRE DIARIO</div>
    </div>
  </div>

  <!-- ===================== FOOTER STRIP ===================== -->
  <div class="footer-strip">
    ARES GYM &middot; M&Oacute;DULO DE CONTROL DE CAJA &amp; MEMBRES&Iacute;AS &middot; DOCUMENTO OFICIAL DE CIERRE
  </div>

</div>

</body>
</html>
