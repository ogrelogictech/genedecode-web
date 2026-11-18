@extends('site.layouts.app')

@section('title', 'Gene Decode — Gift a Membership')
@section('meta_description', 'Gift full access to Gene Decode Deep Dives, Q&A Zooms, and live streams to a friend or family member.')
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

        @if(request()->boolean('gift_success'))
            <div class="card-panel" style="border-color: rgba(124,194,66,0.5); margin-bottom: 22px;">
                <span class="kicker" style="color:#7cc242;">Payment Confirmed</span>
                <h2 style="font-family:var(--serif);font-size:24px;margin:6px 0 10px;color:#fff">Your Gift Membership is Ready!</h2>
                <p style="color:var(--muted);font-size:14.5px;line-height:1.6;margin:0 0 14px">
                    Thank you for gifting a Gene Decode membership. Share the redemption link below with your recipient so they can create their account and activate their access:
                </p>
                <div style="display:flex;gap:8px;align-items:center;background:#090f1d;border:1px solid rgba(255,255,255,0.12);border-radius:8px;padding:10px 12px;">
                    <input id="gift-share-url" type="text" readonly value="{{ url('/join-us?gift_ref=' . urlencode(request('session_id', 'GIFT'))) }}" style="background:transparent;border:0;color:#7cc242;width:100%;font-size:13px;outline:none;">
                    <button type="button" class="btn sm" onclick="navigator.clipboard.writeText(document.getElementById('gift-share-url').value); this.textContent='Copied!';">Copy</button>
                </div>
            </div>
        @endif

        @if(request()->boolean('gift_cancelled'))
            <div class="card-panel" style="border-color: rgba(248,113,113,0.4); margin-bottom: 22px; color:#fca5a5; font-size:14px;">
                Gift checkout was cancelled. You can adjust your selection and try again below.
            </div>
        @endif

        <form method="POST" action="{{ route('gift.checkout') }}" class="card-panel">
            @csrf
            <input type="hidden" name="duration_type" id="gift-duration-type" value="annual">

            <div style="display:flex;align-items:center;gap:14px;margin-bottom:6px">
                <div style="width:46px;height:46px;border-radius:12px;background:rgba(124,194,66,.15);border:1px solid rgba(124,194,66,.4);display:flex;align-items:center;justify-content:center;color:var(--green-l);flex:0 0 auto">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="8" width="18" height="4" rx="1"/><path d="M5 12v9h14v-9M12 8V21M12 8S9.5 3.5 7.5 5s1 3 4.5 3M12 8s2.5-4.5 4.5-3-1 3-4.5 3"/></svg>
                </div>
                <h2 style="font-family:var(--serif);font-size:24px;margin:0;color:#fff">Create a gift</h2>
            </div>
            <p style="color:var(--muted);font-size:14.5px;margin:0 0 22px">Give anyone access to Gene's exclusive Deep Dives and community by gifting them a membership.</p>

            <div class="field"><label>Membership length</label></div>
            <div class="dur-row on" data-type="annual" data-amt="77"><div class="lbl"><span class="rad"></span>1 year</div><span class="amt">$77</span></div>
            <div class="dur-row" data-type="monthly" data-amt="7"><div class="lbl"><span class="rad"></span>1 month</div><span class="amt">$7</span></div>
            <div class="dur-row" data-type="custom" data-amt="custom"><div class="lbl"><span class="rad"></span>Custom months
                <input id="custom-months" name="custom_months" type="number" min="1" max="36" value="3" style="width:66px;background:var(--space-1);border:1px solid var(--line-2);border-radius:8px;padding:6px 8px;color:var(--text);margin-left:6px"></div>
                <span class="amt" id="custom-amt">$21</span>
            </div>

            <div class="field" style="margin-top:18px">
                <label for="gift-qty">Quantity</label>
                <input id="gift-qty" name="quantity" type="number" min="1" max="20" value="1" style="max-width:120px">
            </div>

            <p style="color:var(--faint);font-size:13px;margin:6px 0 20px">After payment, you will get a shareable gift link. The membership must be activated within 12 months or it will expire.</p>

            <button type="submit" class="btn" style="width:100%;justify-content:center">Go to payment</button>
            <p style="color:var(--faint);font-size:12px;margin:12px 0 0;text-align:center">Payments are processed securely through Stripe.</p>
        </form>
        <p style="text-align:center;margin-top:16px"><a href="{{ url('/join-us') }}" style="color:var(--green-l);font-size:14px">Or buy a membership for yourself</a></p>
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
      document.getElementById("gift-duration-type").value = r.getAttribute("data-type");
    });
  });
  var cm = document.getElementById("custom-months");
  if(cm){
    cm.addEventListener("focus", function(){
      document.querySelectorAll(".dur-row").forEach(function(x){ x.classList.remove("on"); });
      cm.closest(".dur-row").classList.add("on");
      document.getElementById("gift-duration-type").value = "custom";
    });
    cm.addEventListener("input", function(){
      var n = parseInt(cm.value || "0", 10) || 0;
      document.getElementById("custom-amt").textContent = "$" + (n * 7);
    });
  }
</script>
@endpush