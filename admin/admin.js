(function(){
  var page = document.body.dataset.page || "";
  // [key, label, href, iconKey, badge]
  var nav = [
    ["dashboard","Dashboard","index.html","grid",""],
    ["__lbl","Content",""],
    ["content","Content Library","content.html","film",""],
    ["live","Live Events","live.html","broadcast","2"],
    ["announcements","Announcements","announcements.html","mail",""],
    ["__lbl","People & Revenue",""],
    ["members","Members","members.html","users",""],
    ["subscriptions","Subscriptions & Billing","subscriptions.html","card",""],
    ["analytics","Analytics","analytics.html","chart",""],
    ["__lbl","Configuration",""],
    ["settings","Settings & Team","settings.html","gear",""]
  ];

  var ic = {
    grid:'<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>',
    film:'<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 4v16M17 4v16M3 9h4M3 15h4M17 9h4M17 15h4"/>',
    broadcast:'<circle cx="12" cy="12" r="2.4"/><path d="M6.3 6.3a8 8 0 000 11.4M17.7 6.3a8 8 0 010 11.4M3.5 3.5a12 12 0 000 17M20.5 3.5a12 12 0 010 17"/>',
    mail:'<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M4 7l8 6 8-6"/>',
    users:'<circle cx="9" cy="8" r="3.2"/><path d="M3.5 20a5.5 5.5 0 0111 0M16 6.5a3 3 0 010 5.8M17.5 20a5.5 5.5 0 00-2.3-4.4"/>',
    card:'<rect x="2.5" y="5" width="19" height="14" rx="2.4"/><path d="M2.5 9.5h19M6 15h4"/>',
    chart:'<path d="M4 20V4M4 20h16M8 20v-6M12 20v-9M16 20v-4M20 20V8"/>',
    gear:'<circle cx="12" cy="12" r="3.2"/><path d="M12 3v2.4M12 18.6V21M3 12h2.4M18.6 12H21M5.6 5.6l1.7 1.7M16.7 16.7l1.7 1.7M18.4 5.6l-1.7 1.7M7.3 16.7l-1.7 1.7"/>',
    search:'<circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/>',
    bell:'<path d="M6 9a6 6 0 1112 0c0 5 2 6 2 6H4s2-1 2-6M10 20a2 2 0 004 0"/>',
    out:'<path d="M15 4h4a1 1 0 011 1v14a1 1 0 01-1 1h-4M10 8l-4 4 4 4M6 12h9"/>'
  };
  function svg(k){return '<svg viewBox="0 0 24 24" aria-hidden="true">'+(ic[k]||"")+'</svg>';}

  var links = nav.map(function(n){
    if(n[0]==="__lbl") return '<div class="sb-lbl">'+n[1]+'</div>';
    var badge = n[4] ? '<span class="badge">'+n[4]+'</span>' : '';
    return '<a href="'+n[2]+'"'+(n[0]===page?' class="on"':'')+'>'+svg(n[3])+'<span>'+n[1]+'</span>'+badge+'</a>';
  }).join("");

  var titles = {dashboard:"Dashboard",content:"Content Library",live:"Live Events",
    announcements:"Announcements & Newsletters",members:"Members",
    subscriptions:"Subscriptions & Billing",analytics:"Analytics",settings:"Settings & Team"};
  var title = titles[page] || "Admin";

  var sidebar =
    '<aside class="sidebar">'+
      '<div class="sb-brand"><img src="../assets/logo.png" alt="Gene Decode">'+
        '<span class="sb-tag">Admin</span></div>'+
      '<nav class="sb-nav">'+links+'</nav>'+
      '<div class="sb-user"><div class="avatar">MS</div>'+
        '<div class="who"><b>Michelle Slusser</b><span>Owner</span></div>'+
        '<a class="out" href="login.html" title="Sign out" aria-label="Sign out">'+svg("out")+'</a>'+
      '</div>'+
    '</aside>';

  var topbar =
    '<div class="topbar">'+
      '<button class="htoggle" aria-label="Toggle menu"><span class="ham"></span></button>'+
      '<h1>'+title+'</h1>'+
      '<div class="search"><svg viewBox="0 0 24 24" aria-hidden="true">'+ic.search+'</svg>'+
        '<input type="search" placeholder="Search content, members, orders"></div>'+
      '<button class="tb-ico" aria-label="Notifications"><span class="dot"></span>'+
        '<svg viewBox="0 0 24 24" aria-hidden="true">'+ic.bell+'</svg></button>'+
    '</div>';

  var shell = document.getElementById("admin-root");
  if(shell){
    shell.className = "admin";
    shell.innerHTML = sidebar +
      '<div class="main-col">'+ topbar +
        '<main class="content">'+ shell.innerHTML +'</main>'+
      '</div>'+
      '<div class="scrim"></div>';
  }

  // off-canvas
  var root = document.querySelector(".admin");
  var toggle = document.querySelector(".htoggle");
  var scrim = document.querySelector(".scrim");
  function close(){ root.classList.remove("open"); }
  if(toggle) toggle.addEventListener("click", function(){ root.classList.toggle("open"); });
  if(scrim) scrim.addEventListener("click", close);
  document.querySelectorAll(".sb-nav a").forEach(function(a){ a.addEventListener("click", close); });

  // generic tabs: <div class="tabs"><button class="tab on" data-target="x">..</button></div> + [data-panel="x"]
  document.querySelectorAll(".tabs").forEach(function(group){
    group.addEventListener("click", function(e){
      var b = e.target.closest(".tab"); if(!b || !group.contains(b)) return;
      group.querySelectorAll(".tab").forEach(function(x){ x.classList.remove("on"); });
      b.classList.add("on");
      var t = b.dataset.target;
      if(t){ document.querySelectorAll("[data-panel]").forEach(function(p){ p.style.display = (p.dataset.panel===t ? "" : "none"); }); }
    });
  });
})();
