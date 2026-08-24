(function(){
  var page = document.body.dataset.page || "";
  var nav = [
    ["home","Home","index.html"],
    ["about","About Gene Decode","about.html"],
    ["schedule","Schedule","schedule.html"],
    ["surface","Surface Area","surface-area.html"],
    ["interviews","Interviews & Videos","interviews.html"],
    ["deep-dives","Deep Dives","deep-dives.html"],
    ["community","Community","community.html"],
    ["donate","Donate","donate.html"],
    ["faq","FAQ","faq.html"],
    ["join","Join Us","join-us.html"],
    ["account","Account","account.html"]
  ];
  var links = nav.map(function(n){
    return '<a href="'+n[2]+'"'+(n[0]===page?' class="on"':'')+'>'+n[1]+'</a>';
  }).join("");

  var header =
    '<div class="ribbon">Refined design concept by <b>OgreLogic</b> for GeneDecode &nbsp;·&nbsp; page mockups on the unified dark/cosmic system</div>'+
    '<header class="site"><div class="wrap brandrow">'+
      '<a href="index.html"><img class="logo" src="assets/logo.png" alt="Gene Decode"></a>'+
      '<span class="lang">Select Language ▾</span>'+
    '</div></header>'+
    '<nav class="main"><div class="wrap">'+links+'</div></nav>';

  var footer =
    '<footer class="site"><div class="wrap foot-top">'+
      '<img class="logo" src="assets/logo.png" alt="Gene Decode" style="height:50px">'+
      '<div class="foot-links">'+
        '<a href="privacy.html">Privacy</a><a href="terms.html">Terms & Conditions</a>'+
        '<a href="subscriber-agreement.html">Subscriber Agreement</a><a href="contact.html">Contact Us</a>'+
        '<a href="#">Blessedforservice.org</a><a href="live.html">Live</a>'+
      '</div>'+
      '<div class="foot-social">'+
        '<img src="assets/telegram.png" alt="Telegram"><img src="assets/rumble.png" alt="Rumble"><img src="assets/truth.png" alt="Truth Social">'+
      '</div>'+
    '</div>'+
    '<div class="wrap disc">Medical & health disclaimer: The content on this platform is for informational and educational purposes only and is not a substitute for professional medical advice. Always consult a qualified provider. By subscribing you agree to the Terms & Conditions and Subscriber Agreement.</div>'+
    '<div class="wrap foot-copy">© 2026 Gene Decode. All Rights Reserved. &nbsp;·&nbsp; Owned-platform design concept by OgreLogic.</div>'+
    '</footer>';

  var h = document.getElementById("site-header");
  if(h) h.outerHTML = header;
  var f = document.getElementById("site-footer");
  if(f) f.outerHTML = footer;
})();
