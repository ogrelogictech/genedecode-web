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

  var langs = ["Select Language","English","Español","Português","Français","Deutsch","Italiano","Nederlands"];
  var langOpts = langs.map(function(l,i){return '<option'+(i===0?' selected':'')+'>'+l+'</option>'}).join("");

  var header =
    '<div class="ribbon">Refined design concept by <b>OgreLogic</b> for GeneDecode &nbsp;·&nbsp; page mockups on the unified dark/cosmic system</div>'+
    '<div class="topbar">'+
    '<header class="site"><div class="wrap brandrow">'+
      '<a href="index.html"><img class="logo" src="assets/logo.png" alt="Gene Decode"></a>'+
      '<select class="lang" aria-label="Select language">'+langOpts+'</select>'+
    '</div></header>'+
    '<nav class="main"><div class="wrap navbar">'+
      '<button class="navtoggle" aria-label="Toggle menu" aria-expanded="false"><span class="ham"></span> Menu</button>'+
      '<div class="navlinks">'+links+'</div>'+
    '</div></nav></div>';

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
    '<div class="wrap foot-copy">© 2026 Gene Decode. All Rights Reserved. &nbsp;·&nbsp; Site by <a class="ogre" href="https://www.ogrelogic.com" target="_blank" rel="noopener">OgreLogic</a>.</div>'+
    '</footer>';

  var h = document.getElementById("site-header"); if(h) h.outerHTML = header;
  var f = document.getElementById("site-footer"); if(f) f.outerHTML = footer;

  // hamburger toggle
  var toggle = document.querySelector(".navtoggle");
  var mainNav = document.querySelector("nav.main");
  if(toggle && mainNav){
    toggle.addEventListener("click", function(){
      var open = mainNav.classList.toggle("open");
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
    });
    mainNav.querySelectorAll(".navlinks a").forEach(function(a){
      a.addEventListener("click", function(){ mainNav.classList.remove("open"); toggle.setAttribute("aria-expanded","false"); });
    });
  }

  // generic tab panels (Account etc.): <div class="tabs"><a data-target="x" class="on">..</a>..</div> + <div data-panel="x">..</div>
  document.querySelectorAll(".tabs").forEach(function(group){
    group.addEventListener("click", function(e){
      var a = e.target.closest("a"); if(!a || !group.contains(a)) return;
      e.preventDefault();
      group.querySelectorAll("a").forEach(function(x){ x.classList.remove("on"); });
      a.classList.add("on");
      var t = a.dataset.target;
      if(t){ document.querySelectorAll("[data-panel]").forEach(function(p){ p.style.display = (p.dataset.panel === t ? "" : "none"); }); }
    });
  });
})();


function closeSiteNotification() {
    const notification = document.getElementById('siteNotification');

    if (notification) {
        notification.remove();
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const profileForm = document.querySelector('form[action*="/account/profile"]');

    if (!profileForm) {
        return;
    }

    profileForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        const formData = new FormData(profileForm);

        try {
            const response = await fetch(profileForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                showSiteNotification('success', data.message);
            } else {
                showSiteNotification('error', data.message, data.errors);
            }

        } catch (error) {
            showSiteNotification(
                'error',
                'Something went wrong. Please try again.'
            );
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const passwordForm = document.getElementById('passwordUpdateForm');

    if (!passwordForm) {
        return;
    }

    passwordForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        const formData = new FormData(passwordForm);

        try {
            const response = await fetch(passwordForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': passwordForm.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                showSiteNotification('success', data.message);

                passwordForm.reset();
            } else {
                showSiteNotification('error', data.message, data.errors);
            }

        } catch (error) {
            showSiteNotification(
                'error',
                'Something went wrong. Please try again.'
            );
        }
    });
});

function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);

    if (!input) {
        return;
    }

    const eyeIcon = button.querySelector('.eye-icon');

    if (input.type === 'password') {
        input.type = 'text';
        button.setAttribute('aria-label', 'Hide password');

        eyeIcon.innerHTML = `
            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
            <circle cx="12" cy="12" r="3"/>
        `;
    } else {
        input.type = 'password';
        button.setAttribute('aria-label', 'Show password');

        eyeIcon.innerHTML = `
            <path d="M3 3l18 18"/>
            <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
            <path d="M9.9 4.2A10.7 10.7 0 0 1 12 4c6.5 0 10 8 10 8a17.4 17.4 0 0 1-3.1 4.4"/>
            <path d="M6.6 6.6C3.7 8.5 2 12 2 12s3.5 8 10 8a10.7 10.7 0 0 0 2.1-.2"/>
        `;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const forgotPasswordForm = document.querySelector(
        'form[action*="/forgot-password"]'
    );

    if (!forgotPasswordForm) {
        return;
    }

    forgotPasswordForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        const formData = new FormData(forgotPasswordForm);

        try {
            const response = await fetch(forgotPasswordForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': forgotPasswordForm.querySelector(
                        'input[name="_token"]'
                    ).value,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                showSiteNotification('success', data.message);
                forgotPasswordForm.reset();
            } else {
                showSiteNotification(
                    'error',
                    data.message,
                    data.errors
                );
            }

        } catch (error) {
            showSiteNotification(
                'error',
                'Something went wrong. Please try again.'
            );
        }
    });
});

function showSiteNotification(type, message, errors = null) {
    const existing = document.getElementById('siteNotification');

    if (existing) {
        existing.remove();
    }

    let content = '';

    if (type === 'success') {
        content = `
            <div class="site-notification-icon success">
                ✓
            </div>

            <h3>Success</h3>

            <p>${message}</p>
        `;
    } else {
        let errorList = '';

        if (errors) {
            Object.values(errors).flat().forEach(function (error) {
                errorList += `<li>${error}</li>`;
            });
        }

        content = `
            <div class="site-notification-icon error">
                !
            </div>

            <h3>Please check the following</h3>

            ${errorList ? `<ul>${errorList}</ul>` : `<p>${message}</p>`}
        `;
    }

    const notification = document.createElement('div');

    notification.id = 'siteNotification';
    notification.className = 'site-notification-overlay';

    notification.innerHTML = `
        <div class="site-notification">

            <button
                type="button"
                class="site-notification-close"
                onclick="closeSiteNotification()"
                aria-label="Close notification"
            >
                &times;
            </button>

            ${content}

        </div>
    `;

    document.body.appendChild(notification);
}
