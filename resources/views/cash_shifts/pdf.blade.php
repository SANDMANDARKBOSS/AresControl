<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Cierre Diario de Caja - Ares Gym</title>
<style>
  @page {
    size: A4;
    margin: 10mm 12mm;
    background-color: #ffffff;
  }

  :root{
    --black: #0c0d0e;
    --dark-slate: #18191c;
    --wine: #781022;
    --wine-bright: #a3152d;
    --wine-light: #fbebee;
    --steel: #94a3b8;
    --steel-dark: #64748b;
    --paper: #f8fafc;
    --border: #e2e8f0;
    --border-dark: #cbd5e1;
    --green-bg: #ecfdf5;
    --green-text: #047857;
    --white: #ffffff;
  }

  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  body{
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    background-color: #ffffff;
    color: #1e293b;
    font-size: 10pt;
    line-height: 1.35;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }

  .sheet{ width: 100%; position: relative; }

  /* HEADER */
  .header{
    background: var(--black);
    color: var(--white);
    padding: 16px 20px;
    border-bottom: 4px solid var(--wine-bright);
    display: table;
    width: 100%;
    margin-bottom: 12px;
  }
  .header-left{ display: table-cell; vertical-align: middle; width: 55%; }
  .header-right{ display: table-cell; vertical-align: middle; text-align: right; width: 45%; }
  .brand-name{
    font-size: 22pt;
    font-weight: 900;
    letter-spacing: 1.5px;
    line-height: 1;
    text-transform: uppercase;
    font-family: Georgia, serif;
  }
  .brand-name span{ color: var(--wine-bright); }
  .brand-sub{
    font-size: 7.5pt;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: var(--steel);
    margin-top: 4px;
    font-weight: 600;
  }
  .doc-tag{
    font-size: 7.5pt;
    letter-spacing: 2px;
    color: var(--wine-bright);
    text-transform: uppercase;
    font-weight: 800;
  }
  .doc-title{
    font-size: 14pt;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    font-weight: 800;
    margin-top: 2px;
    color: #ffffff;
  }
  .doc-folio{ font-size: 8.5pt; color: var(--steel); margin-top: 2px; }

  /* INFO BAR */
  .info-table{
    width: 100%;
    border-collapse: collapse;
    background: var(--paper);
    border: 1px solid var(--border);
    margin-bottom: 14px;
  }
  .info-cell{
    padding: 7px 12px;
    border-right: 1px solid var(--border);
    width: 25%;
    vertical-align: top;
  }
  .info-cell:last-child{ border-right: none; }
  .info-cell label{
    display: block;
    font-size: 6.5pt;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: var(--steel-dark);
    font-weight: 800;
    margin-bottom: 2px;
  }
  .info-cell .fill{ font-size: 9.5pt; font-weight: 700; color: #0f172a; }

  /* SECTIONS */
  .section{ margin-bottom: 13px; page-break-inside: avoid; }
  .section-title-wrap{ display: table; width: 100%; margin-bottom: 6px; page-break-after: avoid; }
  .section-badge{
    display: table-cell;
    width: 20px;
    height: 20px;
    background: var(--wine);
    color: #fff;
    font-size: 8.5pt;
    font-weight: 900;
    text-align: center;
    vertical-align: middle;
    border-radius: 3px;
  }
  .section-text{
    display: table-cell;
    vertical-align: middle;
    padding-left: 8px;
    font-size: 9pt;
    letter-spacing: 1px;
    text-transform: uppercase;
    font-weight: 800;
    color: var(--black);
  }

  /* TABLES */
  table.data-table{ width: 100%; border-collapse: collapse; font-size: 8.5pt; }
  table.data-table th{
    background: var(--dark-slate);
    color: var(--white);
    padding: 5px 8px;
    font-size: 7.5pt;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    font-weight: 700;
    border: 1px solid var(--dark-slate);
  }
  table.data-table td{ padding: 4.5px 8px; border: 1px solid var(--border); color: #334155; }
  table.data-table tr:nth-child(even) td{ background: #fcfcfd; }
  table.data-table .num{ text-align: right; font-variant-numeric: tabular-nums; }
  table.data-table .center{ text-align: center; }

  tr.total-row td{
    background: #f1f5f9 !important;
    font-weight: 800;
    color: #0f172a;
    border-top: 1.5px solid var(--black);
    border-bottom: 1.5px solid var(--black);
  }
  tr.total-row td.wine-val{ color: var(--wine-bright); font-size: 9.5pt; }

  .grid-2{ display: table; width: 100%; table-layout: fixed; }
  .grid-2 .col{ display: table-cell; vertical-align: top; }
  .grid-2 .col.left{ width: 48%; padding-right: 8px; }
  .grid-2 .col.right{ width: 52%; padding-left: 8px; }

  /* CONCILIATION */
  .concil-table{ width: 100%; border-collapse: collapse; font-size: 8.5pt; }
  .concil-table th{
    background: #334155;
    color: #ffffff;
    font-size: 7.5pt;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding: 5px 8px;
    border: 1px solid #334155;
  }
  .concil-table td{ padding: 5px 8px; border: 1px solid var(--border); font-size: 8.5pt; }
  .concil-table tr.highlight-row td{ background: var(--paper); font-weight: 700; }
  .status-badge{
    display: inline-block;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 7pt;
    font-weight: 800;
    letter-spacing: 0.5px;
    text-transform: uppercase;
  }
  .status-ok{ background: var(--green-bg); color: var(--green-text); border: 1px solid #a7f3d0; }
  .status-error{ background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

  .result-box{
    background: var(--black);
    color: #ffffff;
    border-radius: 4px;
    display: table;
    width: 100%;
    table-layout: fixed;
    margin-top: 8px;
    border-left: 5px solid var(--wine-bright);
  }
  .result-cell{ display: table-cell; text-align: center; padding: 8px 10px; border-right: 1px solid #27272a; }
  .result-cell:last-child{ border-right: none; }
  .result-cell label{
    display: block;
    font-size: 6.5pt;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: var(--steel);
    margin-bottom: 2px;
    font-weight: 700;
  }
  .result-cell .val{ font-size: 13pt; font-weight: 900; color: #ffffff; }
  .result-cell.diff .val{ color: #4ade80; }

  .obs-box{
    border: 1px solid var(--border-dark);
    background: #fafafa;
    border-radius: 4px;
    min-height: 38px;
    padding: 6px 10px;
    font-size: 8pt;
    color: #475569;
  }

  .signature-table{ width: 100%; margin-top: 14px; border-collapse: collapse; page-break-inside: avoid; }
  .signature-table td{ width: 50%; text-align: center; padding: 0 35px; vertical-align: top; border: none; }
  .sig-line{ border-top: 1px solid #475569; padding-top: 4px; font-size: 8.5pt; font-weight: 700; color: #0f172a; }
  .sig-role{ font-size: 7pt; letter-spacing: 1.2px; text-transform: uppercase; color: var(--steel-dark); margin-top: 1px; }

  .footer-strip{
    background: var(--wine);
    color: #ffffff;
    text-align: center;
    font-size: 7pt;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    padding: 5px;
    margin-top: 12px;
    font-weight: 600;
  }
</style>
</head>
<body>
<div class="sheet">

  <!-- HEADER -->
  <div class="header">
    <div class="header-left">
      <div class="brand-name">ARES <span>GYM</span></div>
      <div class="brand-sub">Fuerza &middot; Disciplina &middot; Honor</div>
    </div>
    <div class="header-right">
      <div class="doc-tag">Sistema de Membresías</div>
      <div class="doc-title">Cierre y Arqueo Diario</div>
      <div class="doc-folio">Folio N.&deg; <strong>{{ $folio }}</strong></div>
    </div>
  </div>

  <!-- INFO BAR -->
  <table class="info-table">
    <tr>
      <td class="info-cell">
        <label>Fecha de Operación</label>
        <div class="fill">{{ $fecha }}</div>
      </td>
      <td class="info-cell">
        <label>Administrador / Cajero</label>
        <div class="fill">{{ $administrador }}</div>
      </td>
      <td class="info-cell">
        <label>Horario de Jornada</label>
        <div class="fill">{{ $horaApertura }} &ndash; {{ $horaCierre }}</div>
      </td>
      <td class="info-cell">
        <label>Estado del Cierre</label>
        <div class="fill" style="color: var(--wine-bright);">{{ $estado }}</div>
      </td>
    </tr>
  </table>

  <!-- DINERO EFECTIVO Y TRANSFERENCIAS -->
  <div class="grid-2">
    <!-- COLUMNA IZQUIERDA: EFECTIVO -->
    <div class="col left">
      <div class="section">
        <div class="section-title-wrap">
          <div class="section-badge">1</div>
          <div class="section-text">Conteo Físico (Efectivo)</div>
        </div>
        <table class="data-table">
          <thead>
            <tr>
              <th>Denominación</th>
              <th class="num" style="width:28%;">Cant.</th>
              <th class="num" style="width:36%;">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>Billete $100.00</td><td class="num">{{ $b100 }}</td><td class="num">$ {{ $sb100 }}</td></tr>
            <tr><td>Billete $50.00</td><td class="num">{{ $b50 }}</td><td class="num">$ {{ $sb50 }}</td></tr>
            <tr><td>Billete $20.00</td><td class="num">{{ $b20 }}</td><td class="num">$ {{ $sb20 }}</td></tr>
            <tr><td>Billete $10.00</td><td class="num">{{ $b10 }}</td><td class="num">$ {{ $sb10 }}</td></tr>
            <tr><td>Billete $5.00</td><td class="num">{{ $b5 }}</td><td class="num">$ {{ $sb5 }}</td></tr>
            <tr><td>Billete $1.00</td><td class="num">{{ $b1 }}</td><td class="num">$ {{ $sb1 }}</td></tr>
            <tr><td>Monedas Fraccionarias</td><td class="num">{{ $cantMonedas }}</td><td class="num">$ {{ $totalMonedas }}</td></tr>
            <tr class="total-row">
              <td colspan="2">TOTAL EFECTIVO EN CAJA</td>
              <td class="num wine-val">$ {{ $totalEfectivoFisico }}</td>
            </tr>
            <tr>
              <td colspan="2" style="font-size:7.5pt; color:var(--steel-dark);">(-) Fondo Base Inicial (Cambio)</td>
              <td class="num" style="font-size:8pt; color:var(--steel-dark);">- $ {{ $fondoInicial }}</td>
            </tr>
            <tr class="total-row" style="background:#e2e8f0 !important;">
              <td colspan="2">(=) TOTAL RECAUDO EFECTIVO</td>
              <td class="num" style="color:#0f172a;">$ {{ $efectivoVentas }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- COLUMNA DERECHA: TRANSFERENCIAS -->
    <div class="col right">
      <div class="section">
        <div class="section-title-wrap">
          <div class="section-badge">2</div>
          <div class="section-text">Transferencias Bancarias</div>
        </div>
        <table class="data-table">
          <thead>
            <tr>
              <th style="width:26%;">Banco / App</th>
              <th style="width:28%;">N.&deg; Ref.</th>
              <th>Cliente / Plan</th>
              <th class="num" style="width:22%;">Monto</th>
            </tr>
          </thead>
          <tbody>
            @foreach($transfers as $transf)
            <tr>
              <td>Transferencia</td>
              <td><code>{{ $transf->voucher_number ?: 'N/A' }}</code></td>
              <td>{{ $transf->billing_name ?: 'Desconocido' }}</td>
              <td class="num">$ {{ number_format($transf->amount, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
              <td colspan="3">TOTAL TRANSFERENCIAS ({{ $cantTransf }} refs)</td>
              <td class="num wine-val">$ {{ $totalTransferencias }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- CONCILIACIÓN GENERAL -->
  <div class="section" style="margin-top: 4px;">
    <div class="section-title-wrap">
      <div class="section-badge">3</div>
      <div class="section-text">Conciliación y Balance General</div>
    </div>
    <table class="concil-table">
      <thead>
        <tr>
          <th style="text-align:left; width:34%;">Canal / Concepto</th>
          <th style="text-align:right; width:22%;">Esperado (Sistema)</th>
          <th style="text-align:right; width:22%;">Real / Declarado</th>
          <th style="text-align:right; width:12%;">Diferencia</th>
          <th style="text-align:center; width:10%;">Estado</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Fondo Base Inicial (Caja Chica)</td>
          <td class="num">$ {{ $fondoInicial }}</td>
          <td class="num">$ {{ $fondoInicial }}</td>
          <td class="num">$ 0.00</td>
          <td class="center"><span class="status-badge status-ok">OK</span></td>
        </tr>
        <tr>
          <td>Membresías cobradas en <strong>Efectivo</strong></td>
          <td class="num">$ {{ $efectivoSistema }}</td>
          <td class="num">$ {{ $efectivoVentas }}</td>
          <td class="num">$ {{ $difEfectivo }}</td>
          <td class="center"><span class="status-badge {{ $estadoEfectivo === 'CUADRADO' ? 'status-ok' : 'status-error' }}">{{ $estadoEfectivo }}</span></td>
        </tr>
        <tr>
          <td>Membresías cobradas por <strong>Transferencia</strong></td>
          <td class="num">$ {{ $transfSistema }}</td>
          <td class="num">$ {{ $totalTransferencias }}</td>
          <td class="num">$ {{ $difTransferencia }}</td>
          <td class="center"><span class="status-badge {{ $estadoTransf === 'CUADRADO' ? 'status-ok' : 'status-error' }}">{{ $estadoTransf }}</span></td>
        </tr>
        <tr class="highlight-row" style="border-top: 1.5px solid var(--black);">
          <td><strong>TOTAL VENTAS DEL DÍA (MEMBRESÍAS)</strong></td>
          <td class="num"><strong>$ {{ $totalVentasSistema }}</strong></td>
          <td class="num"><strong>$ {{ $totalVentasReal }}</strong></td>
          <td class="num"><strong>$ {{ $difTotal }}</strong></td>
          <td class="center"><span class="status-badge {{ $estadoGeneral === 'CUADRADO' ? 'status-ok' : 'status-error' }}">{{ $estadoGeneral }}</span></td>
        </tr>
      </tbody>
    </table>

    <!-- BANNER RESULTADO -->
    <div class="result-box">
      <div class="result-cell">
        <label>Total Sistema (Ventas + Base)</label>
        <div class="val">$ {{ $totalGeneralSistema }}</div>
      </div>
      <div class="result-cell">
        <label>Total Físico + Bancos</label>
        <div class="val">$ {{ $totalGeneralReal }}</div>
      </div>
      <div class="result-cell diff">
        <label>Diferencia Final</label>
        <div class="val">$ {{ $difFinal }}</div>
      </div>
    </div>
  </div>

  <!-- OBSERVACIONES -->
  <div class="section">
    <div class="section-title-wrap">
      <div class="section-badge">4</div>
      <div class="section-text">Observaciones / Notas del Día</div>
    </div>
    <div class="obs-box">
      {{ $observaciones }}
    </div>
  </div>

  <!-- FIRMAS -->
  <table class="signature-table">
    <tr>
      <td>
        <div class="sig-line">Firma Administrador / Responsable</div>
        <div class="sig-role">Revisión de Caja y Bancos</div>
      </td>
      <td>
        <div class="sig-line">Firma Auditoría / Control</div>
        <div class="sig-role">Verificación de Cierre Diario</div>
      </td>
    </tr>
  </table>

  <!-- FOOTER -->
  <div class="footer-strip">
    Ares Gym &middot; Módulo de Control de Caja &amp; Membresías &middot; Documento Oficial de Cierre
  </div>

</div>
</body>
</html>
