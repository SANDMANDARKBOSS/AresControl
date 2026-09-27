<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ares Gym — Recibo de Pago #{{ str_pad($payment ? $payment->id : ($client->id ?? 1), 4, '0', STR_PAD_LEFT) }}</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Barlow:wght@400;500;600&display=swap');

  :root{
    --black:#0a0a0a;
    --charcoal:#1c1c1c;
    --wine:#7c1f2a;
    --wine-deep:#5a141d;
    --ash:#4a4a4a;
    --smoke:#d9d9d9;
    --white:#f7f7f5;
  }

  *{box-sizing:border-box;}

  body{
    margin:0;
    padding:24px;
    background:#e8e6e1;
    font-family:'Barlow',sans-serif;
    color:var(--charcoal);
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:20px;
  }

  .toolbar{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    justify-content:center;
  }
  .toolbar button, .toolbar a{
    font-family:'Oswald',sans-serif;
    text-transform:uppercase;
    letter-spacing:.05em;
    font-size:13px;
    font-weight:600;
    padding:10px 18px;
    border:2px solid var(--black);
    background:var(--black);
    color:var(--white);
    cursor:pointer;
    border-radius:4px;
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    gap:6px;
    transition:all .2s;
  }
  .toolbar button.secondary, .toolbar a.secondary{
    background:transparent;
    color:var(--black);
  }
  .toolbar button:hover, .toolbar a:hover{ opacity:.85; transform:translateY(-1px); }

  .receipt{
    width:100%;
    max-width:720px;
    background:var(--white);
    box-shadow:0 12px 30px rgba(0,0,0,.25);
    position:relative;
    overflow:hidden;
    border-radius:4px;
  }

  /* Header */
  .head{
    background:var(--black);
    color:var(--white);
    padding:26px 28px;
    display:flex;
    align-items:center;
    gap:18px;
    position:relative;
    border-bottom:5px solid var(--wine);
  }
  .head .logo-img{
    width:74px;
    height:74px;
    object-fit:cover;
    border-radius:50%;
    border:2px solid var(--wine);
    flex-shrink:0;
    background:#1c1c1c;
  }
  .head .brand h1{
    font-family:'Oswald',sans-serif;
    font-size:26px;
    letter-spacing:.06em;
    margin:0;
    text-transform:uppercase;
    color:#f7f7f5;
  }
  .head .brand p{
    margin:4px 0 0;
    font-size:12px;
    color:var(--smoke);
    letter-spacing:.03em;
  }
  .head .doc-tag{
    margin-left:auto;
    text-align:right;
    font-family:'Oswald',sans-serif;
  }
  .head .doc-tag .label{
    font-size:11px;
    letter-spacing:.15em;
    color:var(--wine-deep);
    background:var(--white);
    padding:3px 8px;
    display:inline-block;
    font-weight:600;
    border-radius:2px;
  }
  .head .doc-tag .doc-val{
    display:block;
    margin-top:6px;
    color:var(--white);
    font-family:'Oswald',sans-serif;
    font-size:17px;
    letter-spacing:.05em;
    font-weight:600;
  }
  .head .doc-tag .doc-date{
    display:block;
    margin-top:2px;
    color:var(--smoke);
    font-family:'Barlow',sans-serif;
    font-size:13px;
  }

  /* chevron ribbon divider echoing the logo banner */
  .ribbon{
    height:14px;
    background:repeating-linear-gradient(
      -45deg,
      var(--wine) 0 8px,
      var(--wine-deep) 8px 16px
    );
  }

  .body{
    padding:26px 30px 10px;
  }

  .row{
    display:flex;
    gap:18px;
    margin-bottom:18px;
    flex-wrap:wrap;
  }
  .field{
    flex:1 1 160px;
    display:flex;
    flex-direction:column;
    gap:4px;
  }
  .field label{
    font-family:'Oswald',sans-serif;
    font-size:10.5px;
    letter-spacing:.1em;
    text-transform:uppercase;
    color:var(--ash);
  }
  .field .val{
    font-family:'Barlow',sans-serif;
    font-size:15px;
    font-weight:600;
    padding:6px 0;
    border-bottom:2px solid var(--smoke);
    color:var(--black);
  }

  .section-title{
    font-family:'Oswald',sans-serif;
    font-size:13px;
    letter-spacing:.12em;
    text-transform:uppercase;
    color:var(--wine-deep);
    border-left:4px solid var(--wine);
    padding-left:10px;
    margin:22px 0 14px;
  }

  .divider{
    border:none;
    border-top:1px dashed var(--smoke);
    margin:20px 0;
  }

  table.detail{
    width:100%;
    border-collapse:collapse;
    margin-bottom:6px;
  }
  table.detail th{
    font-family:'Oswald',sans-serif;
    font-size:11px;
    letter-spacing:.08em;
    text-transform:uppercase;
    text-align:left;
    color:var(--white);
    background:var(--charcoal);
    padding:9px 10px;
  }
  table.detail td{
    padding:12px 10px;
    border-bottom:1px solid var(--smoke);
    font-family:'Barlow',sans-serif;
    font-size:14.5px;
    color:var(--charcoal);
  }

  .total-box{
    margin-top:16px;
    display:flex;
    justify-content:flex-end;
  }
  .total-box .inner{
    background:var(--black);
    color:var(--white);
    padding:14px 22px;
    display:flex;
    align-items:center;
    gap:16px;
    min-width:280px;
    justify-content:space-between;
    border-left:5px solid var(--wine);
    border-radius:2px;
  }
  .total-box .inner span.label{
    font-family:'Oswald',sans-serif;
    letter-spacing:.1em;
    font-size:13px;
    text-transform:uppercase;
    color:var(--smoke);
  }
  .total-box .inner span.amount{
    color:var(--white);
    font-family:'Oswald',sans-serif;
    font-size:24px;
    font-weight:700;
    text-align:right;
    letter-spacing:.03em;
  }

  .obs{
    margin-top:14px;
  }
  .obs label{
    font-family:'Oswald',sans-serif;
    font-size:10.5px;
    letter-spacing:.1em;
    text-transform:uppercase;
    color:var(--ash);
    display:block;
    margin-bottom:4px;
  }
  .obs .obs-text{
    width:100%;
    min-height:40px;
    border-bottom:2px solid var(--smoke);
    font-family:'Barlow',sans-serif;
    font-size:13.5px;
    padding:6px 0;
    color:var(--ash);
    line-height:1.5;
  }

  .footer{
    display:flex;
    justify-content:space-between;
    align-items:flex-end;
    padding:36px 30px 24px;
    gap:30px;
  }
  .sign{
    flex:1;
    text-align:center;
  }
  .sign .line{
    border-top:1.5px solid var(--black);
    margin-bottom:6px;
  }
  .sign span{
    font-family:'Oswald',sans-serif;
    font-size:10.5px;
    letter-spacing:.1em;
    text-transform:uppercase;
    color:var(--ash);
  }
  .stamp{
    font-family:'Oswald',sans-serif;
    font-size:10.5px;
    letter-spacing:.1em;
    text-transform:uppercase;
    color:#8a8a8a;
    text-align:center;
    padding:0 30px 18px;
  }

  @media print{
    body{ background:#fff; padding:0; }
    .toolbar{ display:none !important; }
    .receipt{ box-shadow:none !important; max-width:100% !important; border-radius:0; }
    .head{ -webkit-print-color-adjust:exact; print-color-adjust:exact; }
    .ribbon{ -webkit-print-color-adjust:exact; print-color-adjust:exact; }
    .total-box .inner{ -webkit-print-color-adjust:exact; print-color-adjust:exact; }
    table.detail th{ -webkit-print-color-adjust:exact; print-color-adjust:exact; }
  }

  @media (max-width:480px){
    .head{ flex-wrap:wrap; }
    .head .doc-tag{ margin-left:0; text-align:left; width:100%; }
  }
</style>
</head>
<body>

  @php
    $planPrice = $membership && $membership->plan ? (float) $membership->plan->price : 0;
    if ($membership && $membership->group_name && $membership->clients->count() > 1) {
        $planPrice = $planPrice * $membership->clients->count();
    }
    $amountPaid = $payment ? (float) $payment->amount : $planPrice;
    $pendingBalance = $payment ? (float) $payment->pending_balance : 0;
    $isAbono = $pendingBalance > 0;
    $isDebtLiquidation = $membership && $membership->payments->count() > 1 && $pendingBalance == 0;

    $cleanPhone = $client->phone ? preg_replace('/[^0-9]/', '', $client->phone) : '';
    $waUrl = '#';
    if ($cleanPhone) {
        $waNum = str_starts_with($cleanPhone, '593') ? $cleanPhone : ('593' . ltrim($cleanPhone, '0'));
        $waMsg = "🏋️‍♂️ *ARES GYM - COMPROBANTE DE PAGO* 🏋️‍♂️\n";
        $waMsg .= "----------------------------------------\n";
        $waMsg .= "👤 *Atleta:* " . $client->name . " " . $client->last_name . "\n";
        $waMsg .= "🆔 *Cédula:* " . $client->id_card . "\n";
        $waMsg .= "📋 *Plan / Concepto:* " . ($membership && $membership->plan ? $membership->plan->name : 'Membresía') . "\n";
        $waMsg .= "💳 *Método:* " . ($payment && $payment->paymentMethod ? $payment->paymentMethod->name : 'Efectivo') . "\n";
        $waMsg .= "💰 *Monto Pagado:* $" . number_format($amountPaid, 2) . "\n";
        if ($isAbono) {
            $waMsg .= "⚠️ *Saldo Pendiente:* $" . number_format($pendingBalance, 2) . "\n";
            $waMsg .= "📌 *Tipo:* Abono Parcial Autorizado\n";
        } elseif ($isDebtLiquidation) {
            $waMsg .= "✅ *Estado:* Deuda Liquidada al 100% — Paz y Salvo ($0.00)\n";
        } else {
            $waMsg .= "✅ *Estado:* Pago Completo - Al Día\n";
        }
        $waMsg .= "----------------------------------------\n";
        $waMsg .= "🧾 *Factura / Recibo Digital:* " . url()->current() . "\n\n";
        $waMsg .= "¡Gracias por tu disciplina y preferencia en Ares Gym! 💪🔥";

        $waUrl = 'https://wa.me/' . $waNum . '?text=' . urlencode($waMsg);
    }
  @endphp

  <div class="toolbar">
    <button onclick="window.print()">🖨️ Imprimir / Guardar PDF</button>
    @if($cleanPhone)
      <a href="{{ $waUrl }}" target="_blank" style="background:#25D366; border-color:#25D366; color:#fff;">💬 Enviar por WhatsApp</a>
    @endif
    <a href="{{ route('pagos') }}" class="secondary">← Control de Caja</a>
    <a href="{{ route('clientes.show', $client->id) }}" class="secondary">← Perfil del Atleta</a>
  </div>

  <div class="receipt" id="receipt">
    <!-- Header -->
    <div class="head">
      <img src="{{ asset('images/logo_recort.png') }}" alt="Ares Gym" class="logo-img" onerror="this.src='{{ asset('images/logo.png') }}'">
      <div class="brand">
        <h1>Ares Gym</h1>
        <p>Comprobante interno de pago</p>
        <p style="margin-top: 8px; font-family:'Barlow',sans-serif; color: var(--smoke); opacity: 0.8; font-size: 11px;">
          📍 Dirección del Gimnasio<br>
          📞 +593 99 999 9999
        </p>
      </div>
      <div class="doc-tag">
        @if($isAbono)
          <span class="label" style="background:#dc2626; color:#ffffff; font-weight:700;">ABONO PARCIAL</span>
        @elseif($isDebtLiquidation)
          <span class="label" style="background:#16a34a; color:#ffffff; font-weight:700;">PAZ Y SALVO</span>
        @else
          <span class="label">Recibo N.°</span>
        @endif
        <span class="doc-val">#{{ str_pad($payment ? $payment->id : ($client->id ?? 1), 4, '0', STR_PAD_LEFT) }}</span>
        <span class="doc-date">{{ $payment && $payment->created_at ? \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') : \Carbon\Carbon::now()->format('d/m/Y') }}</span>
      </div>
    </div>
    <div class="ribbon"></div>

    <div class="body">
      <!-- Datos del cliente -->
      <div class="section-title">Datos del atleta</div>
      <div class="row">
        <div class="field">
          <label>Nombre completo</label>
          <div class="val">{{ $client->name }} {{ $client->last_name }}</div>
        </div>
        <div class="field">
          <label>Cédula / Identificación</label>
          <div class="val">{{ $client->id_card }}</div>
        </div>
        <div class="field">
          <label>Teléfono</label>
          <div class="val">{{ $client->phone ?? 'N/A' }}</div>
        </div>
      </div>

      <!-- Detalle del pago -->
      <div class="section-title">Detalle del pago</div>
      <table class="detail">
        <thead>
          <tr>
            <th style="width:28%">Concepto</th>
            <th style="width:26%">Plan / Membresía</th>
            <th style="width:24%">Período cubierto</th>
            <th style="width:11%; text-align:right;">Valor Plan</th>
            <th style="width:11%; text-align:right;">{{ $isAbono ? 'Abonado' : 'Total' }}</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <strong>{{ $membership && $membership->plan ? (str_contains(strtolower($membership->plan->name), 'matricula') ? 'Matrícula' : 'Mensualidad / Inscripción') : 'Membresía Gimnasio' }}</strong>
            </td>
            <td>
              {{ $membership && $membership->plan ? $membership->plan->name : 'Membresía General' }}
              @if($membership && $membership->group_name)
                <br><span style="font-size:12px; color:var(--wine); font-weight:600;">(Grupo: {{ $membership->group_name }})</span>
              @endif
            </td>
            <td>
              @if($membership && $membership->start_date && $membership->end_date)
                {{ \Carbon\Carbon::parse($membership->start_date)->format('d M, Y') }} – {{ \Carbon\Carbon::parse($membership->end_date)->format('d M, Y') }}
              @else
                {{ \Carbon\Carbon::now()->format('d M, Y') }}
              @endif
            </td>
            <td style="text-align:right; font-family:'Barlow',sans-serif; font-size:14px; color:var(--ash);">
              ${{ number_format($planPrice, 2) }}
            </td>
            <td style="text-align:right; font-weight:700; font-family:'Oswald',sans-serif; font-size:16px; color: {{ $isAbono ? '#16a34a' : 'inherit' }};">
              ${{ number_format($amountPaid, 2) }}
            </td>
          </tr>
        </tbody>
      </table>

      @if($membership && $membership->clients && $membership->clients->count() > 1)
      <!-- Atletas Integrantes del Plan Grupal -->
      <div class="section-title" style="margin-top: 14px;">Atletas Integrantes del Grupo ({{ $membership->clients->count() }} Socios)</div>
      <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 12px;">
        @foreach($membership->clients as $idx => $mClient)
          <div style="background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; padding: 6px 12px; font-size: 11.5px; display: flex; align-items: center; gap: 6px;">
            <strong style="color: var(--wine);">{{ $idx + 1 }}.</strong>
            <span><strong>{{ $mClient->name }} {{ $mClient->last_name }}</strong></span>
            <span style="color: #64748b; font-family: monospace;">(C.I. {{ $mClient->id_card }})</span>
          </div>
        @endforeach
      </div>
      @endif

      <!-- Método de pago -->
      <div class="row" style="margin-top:16px;">
        <div class="field" style="flex:1 1 200px;">
          <label>Método de pago</label>
          <div class="val" style="display:flex; align-items:center; gap:8px;">
            <span>{{ $payment && $payment->paymentMethod ? $payment->paymentMethod->name : 'Efectivo' }}</span>
            @if($payment && $payment->voucher_number)
              <span style="font-size:12px; color:var(--ash); font-weight:normal;">• Comprobante N.° {{ $payment->voucher_number }}</span>
            @endif
          </div>
        </div>
        <div class="field" style="flex:1 1 200px;">
          <label>Modalidad</label>
          <div class="val">
            @if($isAbono)
              <span style="color:#dc2626; font-weight:700;">Abono Parcial Autorizado</span>
            @else
              <span>Pago Total Cancelado</span>
            @endif
          </div>
        </div>
      </div>

      <!-- Total Box con soporte de Abono y Saldo Pendiente -->
      @if($isAbono)
        <div class="total-box" style="border: 2px solid #dc2626; background: #fff5f5;">
          <div class="inner" style="display:flex; justify-content:space-between; align-items:center;">
            <div>
              <span class="label" style="color: #15803d; font-weight:700; font-size:12px;">Monto Abonado Hoy</span>
              <span class="amount" style="color: #15803d; font-size:24px;">$ {{ number_format($amountPaid, 2) }}</span>
            </div>
            <div style="text-align:right; border-left:2px dashed #f87171; padding-left:24px;">
              <span class="label" style="color: #b91c1c; font-weight:700; font-size:12px;">Saldo Pendiente (Adeudado)</span>
              <span class="amount" style="color: #b91c1c; font-size:24px;">$ {{ number_format($pendingBalance, 2) }}</span>
            </div>
          </div>
        </div>
      @else
        <div class="total-box">
          <div class="inner">
            <span class="label">Total pagado</span>
            <span class="amount">$ {{ number_format($amountPaid, 2) }}</span>
          </div>
        </div>
      @endif

      <hr class="divider">

      <!-- Observaciones -->
      <div class="obs">
        <label>Observaciones</label>
        <div class="obs-text">
          @if($membership && $membership->group_name)
            Membresía correspondiente al plan grupal "{{ $membership->group_name }}".
          @endif
          @if($isAbono)
            <strong>Abono parcial autorizado por Administración.</strong> El atleta mantiene un saldo pendiente de <strong>${{ number_format($pendingBalance, 2) }}</strong> a ser cancelado según las condiciones del gimnasio.
          @else
            Pago completo registrado en recepción Ares Gym. Documento de control interno para respaldo del cliente.
          @endif
        </div>
      </div>
    </div>

    <!-- Footer Signatures -->
    <div class="footer">
      <div class="sign">
        <div class="line"></div>
        <span>Carlos</span><br>
        <span style="font-size: 8px; color: var(--smoke);">RESPONSABLE DE CAJA</span>
      </div>
      <div class="sign">
        <div class="line"></div>
        <span>{{ $client->name }} {{ $client->last_name }}</span><br>
        <span style="font-size: 8px; color: var(--smoke);">FIRMA DEL CLIENTE</span>
      </div>
    </div>
    
    <div class="stamp">Ares Gym · Fuerza forjada — Documento sin validez tributaria</div>
  </div>

  <script>
    @if(request('autoprint') == 1 || request('print') == 1)
      window.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
          window.focus();
          window.print();
        }, 300);
      });
    @endif
  </script>
</body>
</html>
