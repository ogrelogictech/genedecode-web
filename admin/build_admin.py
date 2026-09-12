# -*- coding: utf-8 -*-
# Gene Decode Admin back-office mockup generator.
# Writes the 8 shell pages (login.html is authored separately).
import os
OUT = os.path.dirname(os.path.abspath(__file__))

def page(fname, data_page, title, body):
    html = (
'<!DOCTYPE html>\n<html lang="en"><head>\n'
'<meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />\n'
'<meta name="color-scheme" content="dark" /><title>Gene Decode Admin — ' + title + '</title>\n'
'<link rel="icon" href="../assets/logo.png" /><link rel="stylesheet" href="admin.css" />\n'
'</head><body data-page="' + data_page + '">\n'
'<div id="admin-root">\n' + body + '\n</div>\n'
'<script src="admin.js"></script>\n</body></html>')
    open(os.path.join(OUT, fname), "w", encoding="utf-8").write(html)
    print("wrote", fname)

# ---- shared bits -------------------------------------------------------------
def intro(h, p):
    return '<div class="page-intro"><h2 style="font-family:var(--serif);font-size:22px;margin:0;color:#fff">' + h + '</h2><p>' + p + '</p></div>'

def ico(paths):
    return '<svg viewBox="0 0 24 24" aria-hidden="true">' + paths + '</svg>'

I = {
 "users":'<circle cx="9" cy="8" r="3.2"/><path d="M3.5 20a5.5 5.5 0 0111 0M16 6.5a3 3 0 010 5.8M17.5 20a5.5 5.5 0 00-2.3-4.4"/>',
 "cash":'<rect x="2.5" y="6" width="19" height="12" rx="2"/><circle cx="12" cy="12" r="2.6"/><path d="M6 9v6M18 9v6"/>',
 "film":'<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 4v16M17 4v16M3 9h4M3 15h4M17 9h4M17 15h4"/>',
 "cast":'<circle cx="12" cy="12" r="2.2"/><path d="M6.5 6.5a8 8 0 000 11M17.5 6.5a8 8 0 010 11"/>',
 "up":'<path d="M6 15l6-6 6 6"/>',
 "down":'<path d="M6 9l6 6 6-6"/>',
 "eye":'<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="2.6"/>',
 "clock":'<circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/>',
 "plus":'<path d="M12 5v14M5 12h14"/>',
 "upload":'<path d="M12 16V4M7 9l5-5 5 5M4 20h16"/>',
 "play":'<path d="M6 4l14 8-14 8z"/>',
 "edit":'<path d="M4 20h4L19 9l-4-4L4 16z"/>',
 "trash":'<path d="M4 7h16M9 7V4h6v3M6 7l1 13h10l1-13"/>',
 "mail":'<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M4 7l8 6 8-6"/>',
 "check":'<path d="M4 12l5 5L20 6"/>',
 "dots":'<circle cx="5" cy="12" r="1.6"/><circle cx="12" cy="12" r="1.6"/><circle cx="19" cy="12" r="1.6"/>',
 "info":'<circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/>',
 "cal":'<rect x="3" y="4.5" width="18" height="16" rx="2"/><path d="M3 9h18M8 3v3M16 3v3"/>',
}

def stat(iconkey, label, value, delta, dir="up"):
    d = '<div class="d ' + dir + '">' + (ico(I["up"]) if dir=="up" else (ico(I["down"]) if dir=="down" else "")) + '<span>' + delta + '</span></div>' if delta else ''
    return ('<div class="stat"><div class="k">' + ico(I[iconkey]) + '<span>' + label + '</span></div>'
            '<div class="v">' + value + '</div>' + d + '</div>')

def pill(cls, label, dot=True):
    return '<span class="pill ' + cls + '">' + ('<span class="d"></span>' if dot else '') + label + '</span>'

VID = [
 ("v40vxe6","Envisioning Freedom — See It As Already Done!","Deep Dive","published","156,204","Aug 2, 2026"),
 ("v77jskw","Time &amp; Space Collapsing — Race to the Black Swan","Deep Dive","published","98,410","Jul 28, 2026"),
 ("v786bgm","Pearls of Wisdom — Golden Age Technologies","Pearls","published","41,882","Jul 21, 2026"),
 ("v7ardmw","The General&#39;s Tent — Q&amp;A with Gene Decode","Q&amp;A","processing","0","Sep 8, 2026"),
 ("v7axzmm","Michelle Fielding interviews Gene Decode","Interview","published","22,150","Aug 30, 2026"),
 ("v79kfo2","Pearls of Wisdom — The Anunnaki","Pearls","draft","0","—"),
 ("v77x2a0","Iran War Smokescreen — Liberating Tunnels &amp; DUMBs","Deep Dive","published","74,006","Jul 15, 2026"),
]

# ==============================================================================
# 1. DASHBOARD
# ==============================================================================
bars = [58,64,61,70,74,80,86,92,88,96,101,110]  # subscribers trend (indexed)
rev  = [44,48,47,53,55,59,63,68,66,72,77,83]
months = ["Oct","Nov","Dec","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep"]
chart_cols = ""
mx = float(max(bars))
for i,m in enumerate(months):
    h1 = int(bars[i]/mx*100); h2 = int(rev[i]/mx*100)
    chart_cols += ('<div class="col"><div style="display:flex;gap:3px;align-items:flex-end;width:100%;height:150px;justify-content:center">'
        '<div class="b" style="height:' + str(h1) + '%"></div>'
        '<div class="b alt" style="height:' + str(h2) + '%"></div></div>'
        '<small>' + m + '</small></div>')

recent = [
 ("upload","<b>Ankit</b> uploaded <b>The General&#39;s Tent — Q&amp;A</b>","Processing · 12 min ago"),
 ("users","<b>New member</b> — karen.willis@… joined on the Annual plan","28 min ago"),
 ("cash","<b>Payment received</b> — $77.00 Annual renewal","41 min ago"),
 ("mail","<b>Newsletter sent</b> — &ldquo;This week on Deep Dives&rdquo; to 2,844","2 hours ago"),
 ("cast","<b>Live event scheduled</b> — The General&#39;s Tent, Aug 28","Yesterday"),
]
recent_html = ""
for k,txt,tm in recent:
    recent_html += ('<div class="act-item"><div class="ai">' + ico(I[k]) + '</div>'
        '<div class="ac"><p>' + txt + '</p><span>' + tm + '</span></div></div>')

upcoming = [
 ("28","AUG","The General&#39;s Tent — Live Q&amp;A","7:00 pm CT · Zoom + site","amber","Scheduled"),
 ("05","SEP","Monthly Deep Dive premiere","Members-only decode","green","Ready"),
 ("18","SEP","Community prayer call","6:00 pm CT","grey","Draft"),
]
up_html = ""
for d,mo,t,sub,cl,lab in upcoming:
    up_html += ('<div class="act-item"><div class="ai" style="background:var(--panel-2)">' + ico(I["cal"]) + '</div>'
        '<div class="ac" style="flex:1"><p><b>' + t + '</b></p><span>' + d + ' ' + mo + ' · ' + sub + '</span></div>'
        + pill(cl, lab) + '</div>')

dash_body = (
 intro("Welcome back, Michelle",
   "Here is how Gene Decode is doing today. Everything your team needs to run content, live events, members, and billing lives in the menu on the left.") +
 '<div class="stats">' +
   stat("users","Active subscribers","2,860","+3.4% this month","up") +
   stat("cash","Annual revenue","$230,468","+6.1% vs last year","up") +
   stat("film","Videos published","312","+7 this month","up") +
   stat("cast","Next live event","Aug 28","The General&#39;s Tent","flat") +
 '</div>' +
 '<div class="grid-2">' +
   '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>Subscribers &amp; revenue</h2>'
     '<div class="act"><a href="analytics.html">View analytics</a></div></div>'
     '<p class="panel-sub">Last 12 months. Subscribers growing steadily since the platform move began.</p>'
     '<div class="chart">' + chart_cols + '</div>'
     '<div class="legend"><span><i style="background:var(--green)"></i>Subscribers</span>'
       '<span><i style="background:#3f6ea8"></i>Revenue</span></div></div>' +
   '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>Quick actions</h2></div>'
     '<div style="display:flex;flex-direction:column;gap:10px">'
       '<a class="btn" href="content.html">' + ico(I["upload"]) + 'Upload a video</a>'
       '<a class="btn ghost" href="live.html">' + ico(I["cast"]) + 'Schedule a live event</a>'
       '<a class="btn ghost" href="announcements.html">' + ico(I["mail"]) + 'Send an announcement</a>'
       '<a class="btn ghost" href="members.html">' + ico(I["users"]) + 'View members</a>'
     '</div>'
     '<div class="panel-h" style="margin-top:22px"><span class="bar"></span><h2>Upcoming</h2></div>'
     + up_html + '</div>' +
 '</div>' +
 '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>Recent activity</h2>'
   '<div class="act"><a href="#">See all</a></div></div>'
   '<div class="act-list">' + recent_html + '</div></div>'
)
page("index.html","dashboard","Dashboard", dash_body)

# ==============================================================================
# 2. CONTENT LIBRARY
# ==============================================================================
def status_pill(s):
    return {"published":pill("green","Published"),
            "draft":pill("grey","Draft"),
            "processing":pill("amber","Processing")}[s]
rows = ""
for vid,t,cat,st,views,date in VID:
    href = 'content-edit.html?v=' + vid
    rows += ('<tr><td><div class="title-cell"><div class="thumb" style="background-image:url(../assets/vid/' + vid + '.jpg)"></div>'
        '<div><b><a href="' + href + '" style="color:inherit">' + t + '</a></b><span>' + vid + '.mp4</span></div></div></td>'
        '<td>' + cat + '</td><td>' + status_pill(st) + '</td><td>' + views + '</td><td>' + date + '</td>'
        '<td><div class="tbl-act">'
          '<button class="iconbtn" aria-label="Preview">' + ico(I["play"]) + '</button>'
          '<a class="iconbtn" href="' + href + '" aria-label="Edit">' + ico(I["edit"]) + '</a>'
          '<button class="iconbtn" aria-label="More">' + ico(I["dots"]) + '</button>'
        '</div></td></tr>')

content_body = (
 intro("Content Library",
   "Upload, organize, and publish your Deep Dives, Pearls of Wisdom, Q&amp;A sessions, and interviews. Videos are stored and streamed through Bunny.net.") +
 '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>Upload new video</h2></div>'
   '<div class="dropzone">' + ico(I["upload"]) + '<b>Drag a video here, or click to browse</b>'
     '<p>MP4 or MOV up to 8 GB. Upload continues in the background while you keep working.</p></div>'
   '<div style="margin-top:16px"><div style="display:flex;justify-content:space-between;font-size:13px;color:var(--muted);margin-bottom:6px">'
     '<span>The General&#39;s Tent — Q&amp;A.mp4</span><span>Encoding · 68%</span></div>'
     '<div class="progress"><i style="width:68%"></i></div></div></div>' +
 '<div class="panel-h" style="margin-bottom:14px"><span class="bar"></span><h2>All videos</h2>'
   '<div class="act"><a href="#">312 total</a></div></div>'
 '<div class="toolbar"><div class="grow field" style="margin:0">'
   '<input placeholder="Search videos by title"></div>'
   '<div class="chips"><span class="chip on">All</span><span class="chip">Deep Dive</span>'
     '<span class="chip">Pearls</span><span class="chip">Q&amp;A</span><span class="chip">Interview</span>'
     '<span class="chip">Drafts</span></div></div>'
 '<div class="tablewrap"><table class="tbl"><thead><tr>'
   '<th>Video</th><th>Category</th><th>Status</th><th>Views</th><th>Published</th><th style="text-align:right">Actions</th>'
   '</tr></thead><tbody>' + rows + '</tbody></table></div>'
)
page("content.html","content","Content Library", content_body)

# ==============================================================================
# 3. LIVE EVENTS
# ==============================================================================
live_rows = [
 ("Aug 28, 2026 · 7:00 pm CT","The General&#39;s Tent — Live Q&amp;A","Zoom + Site","amber","Scheduled"),
 ("Sep 05, 2026 · 6:00 pm CT","Monthly Deep Dive premiere","Site (Mux)","blue","Ready"),
 ("Sep 18, 2026 · 6:00 pm CT","Community prayer call","Site (Mux)","grey","Draft"),
 ("Jul 31, 2026 · 7:00 pm CT","Humanitarians Changing the World","Site (Mux)","green","Ended · 1,204 watched"),
 ("Jul 10, 2026 · 7:00 pm CT","Mid-Year Decode Marathon","Zoom + Site","green","Ended · 2,088 watched"),
]
lr = ""
for when,t,where,cl,st in live_rows:
    lr += ('<tr><td><b style="color:#fff">' + t + '</b></td><td>' + when + '</td><td>' + where + '</td>'
        '<td>' + pill(cl, st) + '</td>'
        '<td><div class="tbl-act"><button class="iconbtn" aria-label="Edit">' + ico(I["edit"]) + '</button>'
        '<button class="iconbtn" aria-label="More">' + ico(I["dots"]) + '</button></div></td></tr>')

live_body = (
 intro("Live Events",
   "Schedule and run live streams and Q&amp;A sessions. Streams go out to your members on the site through Mux, and can mirror to Zoom for interactive calls.") +
 '<div class="notice">' + ico(I["info"]) + '<div>Your next event, <b>The General&#39;s Tent — Live Q&amp;A</b>, goes live in <b>3 days</b>. The stream key is ready and the members-only page is set to open 15 minutes before start.</div></div>' +
 '<div class="grid-2">' +
   '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>Next: The General&#39;s Tent</h2>'
     + pill("amber","Scheduled") + '</div>'
     '<div class="player" style="position:relative;aspect-ratio:16/9;border-radius:12px;overflow:hidden;border:1px solid var(--line);background:linear-gradient(120deg,#122a1c,#12294c) center/cover;display:flex;align-items:center;justify-content:center" >'
       '<div style="text-align:center;color:#cfe0d6"><div style="width:70px;height:70px;border-radius:50%;background:rgba(124,194,66,.92);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;color:#08210a">' + ico(I["play"]) + '</div>'
       'Preview available 15 min before start</div></div>'
     '<div class="form-grid" style="margin-top:18px">'
       '<div class="field" style="margin:0"><label>Stream server (RTMP)</label><input value="rtmp://live.mux.com/app" readonly></div>'
       '<div class="field" style="margin:0"><label>Stream key</label><input value="•••••••• 4f2a-9c1b" readonly></div></div>'
     '<div style="display:flex;gap:10px;margin-top:16px;flex-wrap:wrap"><button class="btn">' + ico(I["cast"]) + 'Go live</button>'
       '<button class="btn ghost">Edit event</button><button class="btn ghost">Copy member link</button></div></div>' +
   '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>Schedule an event</h2></div>'
     '<div class="field"><label>Event title</label><input placeholder="e.g. Monthly Deep Dive premiere"></div>'
     '<div class="form-grid"><div class="field"><label>Date</label><input type="date"></div>'
       '<div class="field"><label>Start time (CT)</label><input type="time"></div></div>'
     '<div class="field"><label>Audience</label><select><option>Members only</option><option>Annual members only</option><option>Public (free preview)</option></select></div>'
     '<div class="field"><label>Delivery</label><select><option>Site stream (Mux)</option><option>Zoom + site mirror</option></select></div>'
     '<button class="btn" style="width:100%">' + ico(I["plus"]) + 'Create event</button></div>' +
 '</div>' +
 '<div class="panel-h" style="margin-bottom:14px"><span class="bar"></span><h2>All events</h2></div>'
 '<div class="tablewrap"><table class="tbl"><thead><tr>'
   '<th>Event</th><th>When</th><th>Delivery</th><th>Status</th><th style="text-align:right">Actions</th>'
   '</tr></thead><tbody>' + lr + '</tbody></table></div>'
)
page("live.html","live","Live Events", live_body)

# ==============================================================================
# 4. ANNOUNCEMENTS & NEWSLETTERS
# ==============================================================================
ann_rows = [
 ("This week on Deep Dives","Newsletter","Sep 5, 2026","2,844","61%","green","Sent"),
 ("New Pearls of Wisdom — The Anunnaki","Content alert","Aug 29, 2026","2,830","58%","green","Sent"),
 ("Live Q&amp;A reminder — The General&#39;s Tent","Reminder","Aug 27, 2026","2,860","64%","green","Sent"),
 ("September schedule &amp; prayer call","Newsletter","Sep 12, 2026","—","—","amber","Scheduled"),
]
ar = ""
for t,typ,date,rec,rate,cl,st in ann_rows:
    ar += ('<tr><td><b style="color:#fff">' + t + '</b></td><td>' + typ + '</td><td>' + date + '</td>'
        '<td>' + rec + '</td><td>' + rate + '</td><td>' + pill(cl, st) + '</td></tr>')

ann_body = (
 intro("Announcements &amp; Newsletters",
   "Write once and reach your members by email and on-site notice. Use it for new releases, live-event reminders, and the weekly newsletter.") +
 '<div class="grid-2">' +
   '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>Compose</h2></div>'
     '<div class="field"><label>Subject</label><input placeholder="This week on Deep Dives"></div>'
     '<div class="form-grid"><div class="field"><label>Audience</label>'
       '<select><option>All members (2,860)</option><option>Annual members (1,396)</option><option>Monthly members (1,464)</option><option>Lapsed members</option></select></div>'
       '<div class="field"><label>Send</label><select><option>Now</option><option>Schedule for later</option></select></div></div>'
     '<div class="field"><label>Message</label><textarea rows="7" placeholder="Write your update to the community…"></textarea></div>'
     '<label style="display:flex;gap:9px;align-items:center;color:#d7e2d5;font-size:13.5px;margin-bottom:16px">'
       '<input type="checkbox" checked style="width:auto"> Also post as a banner on the member site</label>'
     '<div style="display:flex;gap:10px;flex-wrap:wrap"><button class="btn">' + ico(I["mail"]) + 'Send to 2,860 members</button>'
       '<button class="btn ghost">Save draft</button><button class="btn ghost">Send test to me</button></div></div>' +
   '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>Performance</h2></div>'
     '<div class="stats" style="grid-template-columns:1fr 1fr;margin:0">'
       + stat("mail","Avg. open rate","61%","+4 pts","up").replace('class="stat"','class="stat" style="padding:16px"')
       + stat("users","Subscribed","2,844","99.4%","flat").replace('class="stat"','class="stat" style="padding:16px"') +
     '</div>'
     '<p class="panel-sub" style="margin-top:16px">Open rates are strong and well above the ~35% average for membership newsletters. Reminders perform best.</p></div>' +
 '</div>' +
 '<div class="panel-h" style="margin-bottom:14px"><span class="bar"></span><h2>Sent &amp; scheduled</h2></div>'
 '<div class="tablewrap"><table class="tbl"><thead><tr>'
   '<th>Title</th><th>Type</th><th>Date</th><th>Recipients</th><th>Open rate</th><th>Status</th>'
   '</tr></thead><tbody>' + ar + '</tbody></table></div>'
)
page("announcements.html","announcements","Announcements", ann_body)

# ==============================================================================
# 5. MEMBERS
# ==============================================================================
mem = [
 ("Karen Willis","karen.willis@gmail.com","Annual","green","Active","Sep 9, 2026","2 hours ago"),
 ("Robert Stein","r.stein@outlook.com","Monthly","green","Active","Sep 8, 2026","Today"),
 ("Anita Prasad","anita.p@yahoo.com","Annual","green","Active","Sep 6, 2026","Yesterday"),
 ("Thomas Vaughn","tvaughn@proton.me","Monthly","amber","Past due","Aug 30, 2026","4 days ago"),
 ("Grace Okafor","grace.okafor@gmail.com","Annual","green","Active","Aug 28, 2026","Today"),
 ("Daniel Marsh","dan.marsh@icloud.com","Monthly","red","Cancelled","Jul 2, 2026","Aug 2, 2026"),
 ("Linda Chen","linda.chen@gmail.com","Annual","green","Active","Aug 24, 2026","3 days ago"),
]
def initials(n):
    p=n.split(); return (p[0][0]+p[-1][0]).upper()
mr = ""
for n,em,plan,cl,st,joined,seen in mem:
    mr += ('<tr><td><div class="who"><div class="avatar">' + initials(n) + '</div>'
        '<div style="min-width:0"><b style="color:#fff;display:block"><a href="member-detail.html" style="color:inherit">' + n + '</a></b>'
        '<span style="color:var(--faint);font-size:12px">' + em + '</span></div></div></td>'
        '<td>' + plan + '</td><td>' + pill(cl, st) + '</td><td>' + joined + '</td><td>' + seen + '</td>'
        '<td><div class="tbl-act"><a class="iconbtn" href="member-detail.html" aria-label="View">' + ico(I["eye"]) + '</a>'
        '<button class="iconbtn" aria-label="More">' + ico(I["dots"]) + '</button></div></td></tr>')

mem_body = (
 intro("Members",
   "Every subscriber in one place. Search, filter by plan or status, view a member&#39;s history, and manage individual accounts. Member and billing data carried over from Uscreen.") +
 '<div class="stats">' +
   stat("users","Total members","2,860","+96 this month","up") +
   stat("check","Active","2,742","95.9% of base","flat") +
   stat("up","New this month","112","+18% vs last","up") +
   stat("down","Cancelled (30d)","24","0.8% churn","down") +
 '</div>' +
 '<div class="toolbar"><div class="grow field" style="margin:0"><input placeholder="Search by name or email"></div>'
   '<div class="chips"><span class="chip on">All</span><span class="chip">Annual</span>'
     '<span class="chip">Monthly</span><span class="chip">Past due</span><span class="chip">Cancelled</span></div>'
   '<button class="btn ghost sm">Export CSV</button></div>'
 '<div class="tablewrap"><table class="tbl"><thead><tr>'
   '<th>Member</th><th>Plan</th><th>Status</th><th>Joined</th><th>Last active</th><th style="text-align:right">Actions</th>'
   '</tr></thead><tbody>' + mr + '</tbody></table></div>'
)
page("members.html","members","Members", mem_body)

# ==============================================================================
# 6. SUBSCRIPTIONS & BILLING
# ==============================================================================
tx = [
 ("Sep 9, 2026","Karen Willis","Annual — renewal","$77.00","green","Paid"),
 ("Sep 9, 2026","Robert Stein","Monthly — renewal","$7.00","green","Paid"),
 ("Sep 8, 2026","Anita Prasad","Annual — new","$77.00","green","Paid"),
 ("Sep 8, 2026","Thomas Vaughn","Monthly — renewal","$7.00","amber","Retrying"),
 ("Sep 7, 2026","Grace Okafor","Annual — renewal","$77.00","green","Paid"),
 ("Sep 6, 2026","Marcus Reed","Monthly — refund","-$7.00","grey","Refunded"),
]
txr = ""
for date,who,desc,amt,cl,st in tx:
    txr += ('<tr><td>' + date + '</td><td><b style="color:#fff">' + who + '</b></td><td>' + desc + '</td>'
        '<td style="font-variant-numeric:tabular-nums">' + amt + '</td><td>' + pill(cl, st) + '</td>'
        '<td><div class="tbl-act"><button class="iconbtn" aria-label="Receipt">' + ico(I["eye"]) + '</button></div></td></tr>')

sub_body = (
 intro("Subscriptions &amp; Billing",
   "Revenue, plans, and payments at a glance. Payments run directly through your own Stripe account, so you own the customer and merchant relationship, not Uscreen.") +
 '<div class="notice">' + ico(I["info"]) + '<div>Connected to <b>Stripe</b> (Gene Decode LLC). Payouts land in your bank on a 2-day rolling schedule. Reconciliation is handled in Stripe.</div></div>' +
 '<div class="stats">' +
   stat("cash","Annual revenue","$230,468","+6.1% YoY","up") +
   stat("up","Monthly recurring","$18,540","+3.1%","up") +
   stat("users","Active subscriptions","2,742","1,396 annual · 1,346 monthly","flat") +
   stat("down","Failed payments","6","Auto-retrying","down") +
 '</div>' +
 '<div class="grid-2">' +
   '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>Plan mix</h2></div>'
     '<div style="display:flex;flex-direction:column;gap:16px">'
       '<div><div style="display:flex;justify-content:space-between;font-size:13.5px;margin-bottom:6px"><span style="color:#fff">Annual — $77/yr</span><span style="color:var(--muted)">1,396 · 51%</span></div>'
         '<div class="progress"><i style="width:51%"></i></div></div>'
       '<div><div style="display:flex;justify-content:space-between;font-size:13.5px;margin-bottom:6px"><span style="color:#fff">Monthly — $7/mo</span><span style="color:var(--muted)">1,346 · 49%</span></div>'
         '<div class="progress"><i style="width:49%;background:#3f6ea8"></i></div></div></div>'
     '<p class="panel-sub" style="margin-top:18px">Annual plans make up just over half of members and the large majority of revenue. Encouraging monthly members to switch to annual is the biggest lever.</p></div>' +
   '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>This month</h2></div>'
     '<div class="act-list">'
       '<div class="act-item"><div class="ac" style="flex:1"><p><b>New subscriptions</b></p></div><b style="color:var(--green-l)">+112</b></div>'
       '<div class="act-item"><div class="ac" style="flex:1"><p><b>Renewals</b></p></div><b style="color:#fff">1,984</b></div>'
       '<div class="act-item"><div class="ac" style="flex:1"><p><b>Cancellations</b></p></div><b style="color:var(--red)">-24</b></div>'
       '<div class="act-item"><div class="ac" style="flex:1"><p><b>Refunds</b></p></div><b style="color:#fff">$63.00</b></div>'
       '<div class="act-item"><div class="ac" style="flex:1"><p><b>Net revenue</b></p></div><b style="color:var(--green-l)">$19,232</b></div>'
     '</div></div>' +
 '</div>' +
 '<div class="panel-h" style="margin-bottom:14px"><span class="bar"></span><h2>Recent transactions</h2>'
   '<div class="act"><a href="#">Open in Stripe</a></div></div>'
 '<div class="tablewrap"><table class="tbl"><thead><tr>'
   '<th>Date</th><th>Member</th><th>Description</th><th>Amount</th><th>Status</th><th style="text-align:right">Receipt</th>'
   '</tr></thead><tbody>' + txr + '</tbody></table></div>'
)
page("subscriptions.html","subscriptions","Subscriptions & Billing", sub_body)

# ==============================================================================
# 7. ANALYTICS
# ==============================================================================
a_bars = [62,58,66,71,68,76,80,84,79,88,93,100]
a_cols = ""
amx=float(max(a_bars))
for i,m in enumerate(months):
    a_cols += ('<div class="col"><div style="display:flex;align-items:flex-end;width:100%;height:150px;justify-content:center">'
        '<div class="b" style="height:' + str(int(a_bars[i]/amx*100)) + '%;max-width:34px"></div></div><small>' + m + '</small></div>')
top = [
 ("Envisioning Freedom — See It As Already Done!","Deep Dive","156,204","2:11:04","91%"),
 ("Time &amp; Space Collapsing — Race to the Black Swan","Deep Dive","98,410","1:32:18","84%"),
 ("Iran War Smokescreen — Liberating Tunnels","Deep Dive","74,006","1:14:52","79%"),
 ("Pearls of Wisdom — Golden Age Technologies","Pearls","41,882","16:20","88%"),
 ("Michelle Fielding interviews Gene Decode","Interview","22,150","58:30","72%"),
]
tr = ""
for t,cat,views,watch,comp in top:
    tr += ('<tr><td><b style="color:#fff">' + t + '</b></td><td>' + cat + '</td><td>' + views + '</td>'
        '<td>' + watch + '</td><td>' + comp + '</td></tr>')
sources = [("Direct / bookmark","46%"),("Email newsletter","24%"),("Rumble","14%"),("Telegram","9%"),("Truth Social","7%")]
src = ""
for s,p in sources:
    src += ('<div style="margin-bottom:13px"><div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:5px">'
        '<span style="color:#fff">' + s + '</span><span style="color:var(--muted)">' + p + '</span></div>'
        '<div class="progress"><i style="width:' + p + '"></i></div></div>')

an_body = (
 intro("Analytics",
   "How your content and membership are performing. Track views, watch time, conversions, and where your audience comes from.") +
 '<div class="stats">' +
   stat("eye","Views (30 days)","438,920","+11.2%","up") +
   stat("clock","Watch time","72,400 hrs","+8.7%","up") +
   stat("users","Unique viewers","41,308","+5.9%","up") +
   stat("up","Visitor to member","4.6%","+0.4 pts","up") +
 '</div>' +
 '<div class="grid-2">' +
   '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>Views over time</h2></div>'
     '<p class="panel-sub">Monthly views, last 12 months.</p>'
     '<div class="chart">' + a_cols + '</div></div>' +
   '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>Traffic sources</h2></div>'
     '<p class="panel-sub">Where members and visitors arrive from.</p>' + src + '</div>' +
 '</div>' +
 '<div class="panel-h" style="margin-bottom:14px"><span class="bar"></span><h2>Top content</h2></div>'
 '<div class="tablewrap"><table class="tbl"><thead><tr>'
   '<th>Video</th><th>Category</th><th>Views</th><th>Avg. watch</th><th>Completion</th>'
   '</tr></thead><tbody>' + tr + '</tbody></table></div>'
)
page("analytics.html","analytics","Analytics", an_body)

# ==============================================================================
# 8. SETTINGS & TEAM
# ==============================================================================
team = [
 ("Michelle Slusser","michelle@genedecode.org","Owner","green","Active"),
 ("Gene (Admin)","gene@genedecode.org","Admin","green","Active"),
 ("Content Editor","editor@genedecode.org","Editor","green","Active"),
 ("Support","support@genedecode.org","Support","grey","Invited"),
]
tmr = ""
for n,em,role,cl,st in team:
    tmr += ('<tr><td><div class="who"><div class="avatar">' + initials(n.replace("(Admin)","")) + '</div>'
        '<div><b style="color:#fff;display:block">' + n + '</b><span style="color:var(--faint);font-size:12px">' + em + '</span></div></div></td>'
        '<td>' + role + '</td><td>' + pill(cl, st) + '</td>'
        '<td><div class="tbl-act"><button class="iconbtn" aria-label="Edit">' + ico(I["edit"]) + '</button>'
        '<button class="iconbtn" aria-label="Remove">' + ico(I["trash"]) + '</button></div></td></tr>')

integrations = [
 ("Stripe","Payments &amp; subscriptions","green","Connected"),
 ("Bunny.net","Video hosting &amp; streaming","green","Connected"),
 ("Mux","Live streaming","green","Connected"),
 ("Pusher","Live chat","green","Connected"),
 ("Mailgun","Email delivery","amber","Action needed"),
]
intr = ""
for name,desc,cl,st in integrations:
    intr += ('<div class="act-item"><div class="ai" style="background:var(--panel-2)">' + ico(I["check"]) + '</div>'
        '<div class="ac" style="flex:1"><p><b>' + name + '</b></p><span>' + desc + '</span></div>'
        + pill(cl, st) + '<button class="btn ghost sm" style="margin-left:12px">Manage</button></div>')

set_body = (
 intro("Settings &amp; Team",
   "Manage your team and their roles, the platform basics, and the services that power the site. Only owners and admins can change these.") +
 '<div class="tabs">'
   '<button class="tab on" data-target="team">Team &amp; Roles</button>'
   '<button class="tab" data-target="general">General</button>'
   '<button class="tab" data-target="integrations">Integrations</button>'
 '</div>' +
 '<div data-panel="team">'
   '<div class="panel-h" style="margin-bottom:14px"><span class="bar"></span><h2>Team members</h2>'
     '<div class="act"><button class="btn sm">' + ico(I["plus"]) + 'Invite member</button></div></div>'
   '<div class="tablewrap"><table class="tbl"><thead><tr><th>Person</th><th>Role</th><th>Status</th><th style="text-align:right">Actions</th></tr></thead>'
     '<tbody>' + tmr + '</tbody></table></div>'
   '<div class="panel" style="margin-top:20px"><div class="panel-h"><span class="bar"></span><h2>What each role can do</h2></div>'
     '<div class="act-list">'
       '<div class="act-item"><div class="ac"><p><b>Owner</b></p><span>Full access, including billing and team management.</span></div></div>'
       '<div class="act-item"><div class="ac"><p><b>Admin</b></p><span>Everything except deleting the account or changing the owner.</span></div></div>'
       '<div class="act-item"><div class="ac"><p><b>Editor</b></p><span>Upload and manage content, schedule live events, send announcements.</span></div></div>'
       '<div class="act-item"><div class="ac"><p><b>Support</b></p><span>View members and billing, help with accounts. No content or settings.</span></div></div>'
     '</div></div></div>' +
 '<div data-panel="general" style="display:none">'
   '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>Site details</h2></div>'
     '<div class="form-grid"><div class="field"><label>Site name</label><input value="Gene Decode"></div>'
       '<div class="field"><label>Primary domain</label><input value="genedecode.org"></div>'
       '<div class="field"><label>Support email</label><input value="support@genedecode.org"></div>'
       '<div class="field"><label>Time zone</label><select><option>Central Time (CT)</option><option>Mountain Time (MT)</option></select></div></div>'
     '<div class="field full"><label>Membership pricing</label><input value="Monthly $7 / Annual $77" readonly></div>'
     '<button class="btn">Save changes</button></div></div>' +
 '<div data-panel="integrations" style="display:none">'
   '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>Connected services</h2></div>'
     '<p class="panel-sub">These power your video, live streaming, payments, chat, and email. Your team owns every account.</p>'
     '<div class="act-list">' + intr + '</div></div></div>'
)
page("settings.html","settings","Settings & Team", set_body)

# ==============================================================================
# 9. CONTENT EDIT (detail screen for a single video)
# ==============================================================================
ce_body = (
 '<div class="crumbs"><a href="content.html">Content Library</a> / Edit video</div>' +
 intro("Edit video","Update the details, access level, and publish status for this video. Changes save to the live site right away.") +
 '<div class="grid-2">' +
   '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>Video details</h2></div>'
     '<div class="field"><label>Title</label><input value="Envisioning Freedom — See It As Already Done!"></div>'
     '<div class="form-grid"><div class="field"><label>Category</label><select><option>Deep Dive</option><option>Pearls of Wisdom</option><option>Q&amp;A</option><option>Interview</option></select></div>'
       '<div class="field"><label>Access level</label><select><option>Members only</option><option>Annual members only</option><option>Public (free preview)</option></select></div></div>'
     '<div class="form-grid"><div class="field"><label>Status</label><select><option>Published</option><option>Draft</option><option>Scheduled</option></select></div>'
       '<div class="field"><label>Publish date</label><input type="date" value="2026-08-02"></div></div>'
     '<div class="field"><label>Description</label><textarea rows="5">A full-length Deep Dive decode. Members get the complete session plus the companion notes and the live Q&amp;A replay. Progress is saved automatically across devices.</textarea></div>'
     '<div class="field"><label>Tags</label><input value="freedom, manifestation, deep dive"></div>'
     '<div style="display:flex;gap:10px"><button class="btn">Save changes</button><a class="btn ghost" href="content.html">Cancel</a></div></div>' +
   '<div>'
     '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>Preview</h2></div>'
       '<img src="../assets/vid/v40vxe6.jpg" alt="Envisioning Freedom thumbnail" style="width:100%;border-radius:10px;border:1px solid var(--line-2)">'
       '<button class="btn ghost" style="width:100%;margin-top:12px">' + ico(I["upload"]) + 'Replace video file</button>'
       '<div class="act-list" style="margin-top:8px">'
         '<div class="act-item"><div class="ac" style="flex:1"><p><b>Views</b></p></div><b style="color:#fff">156,204</b></div>'
         '<div class="act-item"><div class="ac" style="flex:1"><p><b>Duration</b></p></div><b style="color:#fff">2:40:18</b></div>'
         '<div class="act-item"><div class="ac" style="flex:1"><p><b>Uploaded</b></p></div><b style="color:#fff">Aug 2, 2026</b></div>'
         '<div class="act-item"><div class="ac" style="flex:1"><p><b>Streaming</b></p></div>' + pill("green","Bunny · Ready") + '</div>'
       '</div></div>'
     '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>Danger zone</h2></div>'
       '<p class="panel-sub">Unpublishing hides the video from members. Deleting removes it for good.</p>'
       '<div style="display:flex;gap:10px;flex-wrap:wrap"><button class="btn ghost">Unpublish</button><button class="btn danger">' + ico(I["trash"]) + 'Delete video</button></div></div>'
   '</div>' +
 '</div>'
)
page("content-edit.html","content","Content Library", ce_body)

# ==============================================================================
# 10. MEMBER DETAIL (detail screen for a single member)
# ==============================================================================
md_body = (
 '<div class="crumbs"><a href="members.html">Members</a> / Karen Willis</div>' +
 '<div style="display:flex;align-items:center;gap:14px;margin-bottom:22px;flex-wrap:wrap">'
   '<div class="avatar" style="width:52px;height:52px;font-size:16px">KW</div>'
   '<div style="min-width:0"><h2 style="font-family:var(--serif);font-size:22px;margin:0;color:#fff">Karen Willis</h2>'
     '<p style="color:var(--muted);margin:0;font-size:13.5px">karen.willis@gmail.com</p></div>'
   '<div style="margin-left:auto;display:flex;gap:8px;align-items:center;flex-wrap:wrap">' + pill("green","Active") + pill("grey","Annual member") + '</div>'
 '</div>' +
 '<div class="grid-2">' +
   '<div>'
     '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>Membership</h2><div class="act"><a href="subscriptions.html">Open in billing</a></div></div>'
       '<div class="act-list">'
         '<div class="act-item"><div class="ac" style="flex:1"><p><b>Plan</b></p></div><b style="color:#fff">Annual · $77 / year</b></div>'
         '<div class="act-item"><div class="ac" style="flex:1"><p><b>Status</b></p></div>' + pill("green","Active") + '</div>'
         '<div class="act-item"><div class="ac" style="flex:1"><p><b>Next renewal</b></p></div><b style="color:#fff">Sep 9, 2027</b></div>'
         '<div class="act-item"><div class="ac" style="flex:1"><p><b>Payment method</b></p></div><b style="color:#fff">Visa ending 4242</b></div>'
       '</div>'
       '<div style="display:flex;gap:10px;margin-top:14px;flex-wrap:wrap"><button class="btn ghost sm">Change plan</button><button class="btn ghost sm">Issue refund</button><button class="btn danger sm">Cancel membership</button></div></div>'
     '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>Billing history</h2></div>'
       '<div class="tablewrap"><table class="tbl"><thead><tr><th>Date</th><th>Description</th><th>Amount</th><th>Status</th></tr></thead><tbody>'
         '<tr><td>Sep 9, 2026</td><td>Annual membership</td><td>$77.00</td><td>' + pill("green","Paid") + '</td></tr>'
         '<tr><td>Sep 9, 2025</td><td>Annual membership</td><td>$77.00</td><td>' + pill("green","Paid") + '</td></tr>'
         '<tr><td>Sep 9, 2024</td><td>Annual membership</td><td>$77.00</td><td>' + pill("green","Paid") + '</td></tr>'
       '</tbody></table></div></div>'
   '</div>' +
   '<div>'
     '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>Profile</h2></div>'
       '<div class="act-list">'
         '<div class="act-item"><div class="ac" style="flex:1"><p><b>Member since</b></p></div><b style="color:#fff">Sep 2024</b></div>'
         '<div class="act-item"><div class="ac" style="flex:1"><p><b>Last active</b></p></div><b style="color:#fff">2 hours ago</b></div>'
         '<div class="act-item"><div class="ac" style="flex:1"><p><b>Location</b></p></div><b style="color:#fff">Phoenix, AZ</b></div>'
         '<div class="act-item"><div class="ac" style="flex:1"><p><b>Videos watched</b></p></div><b style="color:#fff">142</b></div>'
       '</div></div>'
     '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>Recent activity</h2></div>'
       '<div class="act-list">'
         '<div class="act-item"><div class="ai">' + ico(I["play"]) + '</div><div class="ac"><p>Watched <b>Envisioning Freedom</b></p><span>2 hours ago</span></div></div>'
         '<div class="act-item"><div class="ai">' + ico(I["cast"]) + '</div><div class="ac"><p>Joined live <b>The General&#39;s Tent</b></p><span>Aug 28</span></div></div>'
         '<div class="act-item"><div class="ai">' + ico(I["cash"]) + '</div><div class="ac"><p>Renewed <b>Annual membership</b></p><span>Sep 9</span></div></div>'
       '</div></div>'
     '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>Actions</h2></div>'
       '<div style="display:flex;flex-direction:column;gap:10px">'
         '<button class="btn ghost">' + ico(I["mail"]) + 'Send password reset</button>'
         '<button class="btn ghost">' + ico(I["mail"]) + 'Message member</button>'
         '<button class="btn danger">Suspend account</button>'
       '</div></div>'
   '</div>' +
 '</div>'
)
page("member-detail.html","members","Members", md_body)

# ==============================================================================
# 11. DISCOUNTS & COUPONS  (Stage 1 core: coupon/discount codes)
# ==============================================================================
disc_rows = [
 ("WELCOME25","25% off","First payment","All plans","142","green","Active","No expiry"),
 ("ANNUAL10","$10 off","Annual only","Annual","58","green","Active","Dec 31, 2026"),
 ("GENELIVE","1 month free","New members","Monthly","31","green","Active","Sep 30, 2026"),
 ("EASTER26","20% off","First payment","All plans","410","grey","Expired","Apr 30, 2026"),
]
dr = ""
for code,typ,applies,plan,red,cl,st,exp in disc_rows:
    dr += ('<tr><td><b style="color:#fff;font-family:var(--serif);letter-spacing:.5px">' + code + '</b></td>'
        '<td>' + typ + '</td><td>' + plan + '</td><td>' + red + '</td><td>' + pill(cl, st) + '</td><td>' + exp + '</td>'
        '<td><div class="tbl-act"><button class="iconbtn" aria-label="Edit">' + ico(I["edit"]) + '</button>'
        '<button class="iconbtn" aria-label="More">' + ico(I["dots"]) + '</button></div></td></tr>')

disc_body = (
 intro("Discounts &amp; Coupons",
   "Create and manage coupon codes for your membership plans. Codes work at checkout and are honored by your own Stripe account.") +
 '<div class="grid-2">' +
   '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>Create a coupon</h2></div>'
     '<div class="field"><label>Coupon code</label><input placeholder="e.g. WELCOME25" style="text-transform:uppercase"></div>'
     '<div class="form-grid"><div class="field"><label>Discount type</label><select><option>Percentage off</option><option>Fixed amount off</option><option>Free time (months)</option></select></div>'
       '<div class="field"><label>Amount</label><input placeholder="25"></div></div>'
     '<div class="form-grid"><div class="field"><label>Applies to</label><select><option>All plans</option><option>Monthly only</option><option>Annual only</option></select></div>'
       '<div class="field"><label>Duration</label><select><option>First payment only</option><option>Forever</option><option>First 3 payments</option></select></div></div>'
     '<div class="form-grid"><div class="field"><label>Usage limit</label><input placeholder="Unlimited"></div>'
       '<div class="field"><label>Expires</label><input type="date"></div></div>'
     '<button class="btn" style="width:100%">' + ico(I["plus"]) + 'Create coupon</button></div>' +
   '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>This month</h2></div>'
     '<div class="act-list">'
       '<div class="act-item"><div class="ac" style="flex:1"><p><b>Active codes</b></p></div><b style="color:#fff">3</b></div>'
       '<div class="act-item"><div class="ac" style="flex:1"><p><b>Redemptions</b></p></div><b style="color:var(--green-l)">231</b></div>'
       '<div class="act-item"><div class="ac" style="flex:1"><p><b>New members from codes</b></p></div><b style="color:#fff">88</b></div>'
       '<div class="act-item"><div class="ac" style="flex:1"><p><b>Discount given</b></p></div><b style="color:#fff">$1,940</b></div>'
     '</div>'
     '<p class="panel-sub" style="margin-top:16px">Coupons apply at checkout and are validated against Stripe, so discounts always match what members are actually billed.</p></div>' +
 '</div>' +
 '<div class="panel-h" style="margin-bottom:14px"><span class="bar"></span><h2>All coupons</h2></div>'
 '<div class="tablewrap"><table class="tbl"><thead><tr>'
   '<th>Code</th><th>Discount</th><th>Applies to</th><th>Redemptions</th><th>Status</th><th>Expires</th><th style="text-align:right">Actions</th>'
   '</tr></thead><tbody>' + dr + '</tbody></table></div>'
)
page("discounts.html","discounts","Discounts & Coupons", disc_body)

# ==============================================================================
# 12. PAGES & SITE CONTENT  (content management for the non-video pages)
# ==============================================================================
site_pages = [
 ("Home","/","Published","Sep 4, 2026"),
 ("About Gene Decode","/about","Published","Aug 30, 2026"),
 ("Schedule &amp; Newsletters","/schedule","Published","Sep 2, 2026"),
 ("Surface Area","/surface-area","Published","Aug 28, 2026"),
 ("Interviews &amp; Videos","/interviews","Published","Sep 1, 2026"),
 ("Join Us (pricing)","/join-us","Published","Aug 18, 2026"),
 ("Donate","/donate","Published","Aug 15, 2026"),
 ("FAQ","/faq","Published","Aug 20, 2026"),
 ("Contact Us","/contact","Published","Aug 15, 2026"),
 ("Privacy Policy","/privacy","Draft","Pending copy"),
 ("Terms &amp; Conditions","/terms","Draft","Pending copy"),
 ("Subscriber Agreement","/subscriber-agreement","Draft","Pending copy"),
]
pr = ""
for name,path,st,upd in site_pages:
    stp = pill("green","Published") if st=="Published" else pill("amber","Draft")
    pr += ('<tr><td><b style="color:#fff">' + name + '</b></td><td style="color:var(--muted)">' + path + '</td>'
        '<td>' + stp + '</td><td>' + upd + '</td>'
        '<td><div class="tbl-act"><button class="iconbtn" aria-label="Edit">' + ico(I["edit"]) + '</button>'
        '<button class="iconbtn" aria-label="View">' + ico(I["eye"]) + '</button></div></td></tr>')

pages_body = (
 intro("Pages &amp; Site Content",
   "Edit the words and images on your public pages, without touching code. Video and live content are managed in their own sections.") +
 '<div class="notice">' + ico(I["info"]) + '<div>The three legal pages are marked <b>Draft</b> until you provide the final approved copy. Everything else is live.</div></div>' +
 '<div class="grid-2">' +
   '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>Quick edit — Home hero</h2><div class="act"><a href="../index.html" target="_blank">View live</a></div></div>'
     '<div class="field"><label>Headline</label><input value="Deep Dives with Gene Decode"></div>'
     '<div class="field"><label>Sub-headline</label><textarea rows="3">Sharing the truth and hidden knowledge of the world, and facilitating your personal and spiritual growth on the journey into the Great Awakening.</textarea></div>'
     '<div class="form-grid"><div class="field"><label>Primary button</label><input value="Subscribe to Deep Dives"></div>'
       '<div class="field"><label>Secondary button</label><input value="Watch the introduction"></div></div>'
     '<button class="btn">Save changes</button></div>' +
   '<div class="panel"><div class="panel-h"><span class="bar"></span><h2>Site basics</h2></div>'
     '<div class="act-list">'
       '<div class="act-item"><div class="ac" style="flex:1"><p><b>Pages published</b></p></div><b style="color:#fff">9</b></div>'
       '<div class="act-item"><div class="ac" style="flex:1"><p><b>Drafts</b></p></div><b style="color:var(--amber)">3</b></div>'
       '<div class="act-item"><div class="ac" style="flex:1"><p><b>Navigation</b></p></div><b style="color:#fff">11 links</b></div>'
       '<div class="act-item"><div class="ac" style="flex:1"><p><b>Footer &amp; social</b></p></div>' + pill("green","Set") + '</div>'
     '</div>'
     '<button class="btn ghost" style="width:100%;margin-top:8px">' + ico(I["edit"]) + 'Edit navigation &amp; footer</button></div>' +
 '</div>' +
 '<div class="panel-h" style="margin-bottom:14px"><span class="bar"></span><h2>All pages</h2></div>'
 '<div class="tablewrap"><table class="tbl"><thead><tr>'
   '<th>Page</th><th>URL</th><th>Status</th><th>Last updated</th><th style="text-align:right">Actions</th>'
   '</tr></thead><tbody>' + pr + '</tbody></table></div>'
)
page("pages.html","pages","Pages & Site Content", pages_body)

# ==============================================================================
# 13. COMMUNITY & COMMENTS moderation  (supports the community feature)
# ==============================================================================
mod_items = [
 ("James P.","JP","Comment on <b>Envisioning Freedom</b>","This changed how I see everything, thank you Gene.","2 hours ago","clean"),
 ("Sandra K.","SK","Community post in <b>Prayer &amp; Support</b>","Please keep my family in your prayers this week.","5 hours ago","clean"),
 ("hidden_user_88","H8","Comment on <b>Iran War Smokescreen</b>","Check out this link for free crypto&#8230;","Yesterday","flag"),
 ("Robert S.","RS","Community post in <b>Deep Dive Discussion</b>","What did everyone think of the timeline in part 2?","Yesterday","clean"),
]
mi = ""
for name,ini,where,text,tm,flag in mod_items:
    tagp = pill("red","Flagged") if flag=="flag" else pill("grey","New")
    remove_btn = ('<button class="btn danger sm">' + ico(I["trash"]) + 'Remove</button>' if flag=="flag"
                  else '<button class="btn ghost sm">' + ico(I["trash"]) + 'Remove</button>')
    mi += ('<div class="panel" style="margin-bottom:14px"><div style="display:flex;gap:12px;align-items:flex-start">'
        '<div class="avatar">' + ini + '</div>'
        '<div style="flex:1;min-width:0"><div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:4px">'
          '<b style="color:#fff">' + name + '</b>' + tagp + '<span style="color:var(--faint);font-size:12px">' + tm + '</span></div>'
          '<p style="color:var(--muted);font-size:13px;margin:0 0 6px">' + where + '</p>'
          '<p style="color:#dbe5ee;font-size:14px;margin:0">&ldquo;' + text + '&rdquo;</p></div></div>'
        '<div style="display:flex;gap:8px;margin-top:12px;flex-wrap:wrap;justify-content:flex-end">'
          '<button class="btn ghost sm">' + ico(I["check"]) + 'Approve</button>' + remove_btn +
          '<button class="btn ghost sm">Ban user</button></div></div>')

mod_body = (
 intro("Community &amp; Comments",
   "Keep the community safe. Review flagged items, moderate posts and video comments, and manage members who break the rules.") +
 '<div class="notice">' + ico(I["info"]) + '<div>Community and live chat are part of the <b>fast-follow (Stage 2)</b> plan. This screen shows how your team will moderate them once they are live.</div></div>' +
 '<div class="stats">' +
   stat("info","Pending review","3","","flat") +
   stat("users","Posts this week","48","+12","up") +
   stat("mail","Comments this week","214","+31","up") +
   stat("info","Flagged","1","Needs action","flat") +
 '</div>' +
 '<div class="tabs">'
   '<button class="tab on" data-target="queue">Needs review</button>'
   '<button class="tab" data-target="posts">Community posts</button>'
   '<button class="tab" data-target="comments">Video comments</button>'
 '</div>' +
 '<div data-panel="queue">' + mi + '</div>'
 '<div data-panel="posts" style="display:none"><div class="panel"><p class="panel-sub" style="margin:0">All community posts appear here with the same approve, remove, and ban controls.</p></div></div>'
 '<div data-panel="comments" style="display:none"><div class="panel"><p class="panel-sub" style="margin:0">All video comments appear here, grouped by video, with the same moderation controls.</p></div></div>'
)
page("moderation.html","community","Community & Comments", mod_body)

print("\nAll admin shell pages generated.")
