@extends('site.layouts.app')

@section('title', 'Gene Decode — Watch')
@section('page', 'deep-dives')

@section('content')

<section class="pagehero">
    <div class="wrap">
        <span class="kicker">Membership</span>
        <h1>Gift a Membership</h1>
        <p>Give someone full access to Gene Decode Deep Dives. After payment you get a shareable gift link to send whenever you like.</p>
    </div>
</section>

<section>
    <div class="wrap" style="max-width:640px">
        <div class="card-panel">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:6px">
                <div style="width:46px;height:46px;border-radius:12px;background:rgba(124,194,66,.15);border:1px solid rgba(124,194,66,.4);display:flex;align-items:center;justify-content:center;color:var(--green-l);flex:0 0 auto">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="8" width="18" height="4" rx="1"/><path d="M5 12v9h14v-9M12 8V21M12 8S9.5 3.5 7.5 5s1 3 4.5 3M12 8s2.5-4.5 4.5-3-1 3-4.5 3"/></svg>
                </div>
                <h2 style="font-family:var(--serif);font-size:24px;margin:0;color:#fff">Create a gift</h2>
            </div>
            <p style="color:var(--muted);font-size:14.5px;margin:0 0 22px">Give anyone access to Gene's exclusive Deep Dives and community by gifting them a membership.</p>

            <div class="field"><label>Membership length</label></div>
            <div class="dur-row on" data-amt="77"><div class="lbl"><span class="rad"></span>1 year</div><span class="amt">$77</span></div>
            <div class="dur-row" data-amt="7"><div class="lbl"><span class="rad"></span>1 month</div><span class="amt">$7</span></div>
            <div class="dur-row" data-amt="custom"><div class="lbl"><span class="rad"></span>Custom months
                <input id="custom-months" type="number" min="1" value="3" style="width:66px;background:var(--space-1);border:1px solid var(--line-2);border-radius:8px;padding:6px 8px;color:var(--text);margin-left:6px"></div>
                <span class="amt" id="custom-amt">$21</span>
            </div>

            <div class="field" style="margin-top:18px"><label>Quantity</label><input type="number" min="1" value="1" style="max-width:120px"></div>

            <p style="color:var(--faint);font-size:13px;margin:6px 0 20px">After payment, you will get a shareable gift link. The membership must be activated within 12 months or it will expire.</p>

            <button class="btn" style="width:100%;justify-content:center">Go to payment</button>
            <p style="color:var(--faint);font-size:12px;margin:12px 0 0;text-align:center">Payments are processed securely through Stripe.</p>
        </div>
        <p style="text-align:center;margin-top:16px"><a href="join-us.html" style="color:var(--green-l);font-size:14px">Or buy a membership for yourself</a></p>
    </div>
</section>

@endsection

@push('scripts')

<script>
  document.querySelectorAll(".dur-row").forEach(function(r){
    r.addEventListener("click", function(e){
      if(e.target.id === "custom-months") return;
      document.querySelectorAll(".dur-row").forEach(function(x){ x.classList.remove("on"); });
      r.classList.add("on");
    });
  });
  var cm = document.getElementById("custom-months");
  if(cm){
    cm.addEventListener("input", function(){
      var n = parseInt(cm.value || "0", 10) || 0;
      document.getElementById("custom-amt").textContent = "$" + (n * 7);
    });
  }
</script>


@endpush