@extends('site.layouts.app')

@section('title', 'Gene Decode — Community')

@section('page', 'community')

@section('content')

<section>
    <div class="wrap">

        <div class="comm">

            <div class="side">

                <a class="on">🏠 Home</a>
                <a>🔔 Notifications</a>
                <a>✨ AI Assistant</a>
                <a>📝 Drafts</a>
                <a>📅 Scheduled</a>

                <div class="lbl">Public channels</div>

                <a class="on">🔶 general</a>
                <a>🙏 envisioningforlife</a>
                <a>🎥 deep-dive-notes</a>

                <div class="lbl">Private</div>

                <a>🔒 team</a>

            </div>

            <div>

                <div class="composer">

                    <input
                        class="field"
                        style="margin:0"
                        placeholder="Share something with the community…"
                    >

                    <div style="display:flex;gap:8px;margin-top:10px">
                        <button class="btn sm ghost">Media</button>
                        <button class="btn sm ghost">Poll</button>
                        <button class="btn sm" style="margin-left:auto">Post</button>
                    </div>

                </div>

                <div class="card-panel" style="margin-bottom:16px;padding:14px 18px">

                    <div class="kicker" style="margin-bottom:6px">
                        Pinned
                    </div>

                    <a style="color:#e9f0e6">
                        Welcome everyone to the Gene Decode Deep Dives community.
                        Please read the guidelines and introduce yourself in general.
                    </a>

                </div>

                <div style="display:flex;align-items:center;margin-bottom:12px">

                    <span class="kicker">general</span>

                    <span style="margin-left:auto;color:var(--faint);font-size:13px">
                        Sorted by newest
                    </span>

                </div>

                <div class="post">

                    <div class="who">
                        <div class="avatar">MA</div>

                        <div>
                            <b>Marcus</b>
                            <span>in general · Aug 16</span>
                        </div>
                    </div>

                    <p>
                        The CED protocol for removing shedded material from the blood:
                        Gene mentioned a spoonful of high-potency vitamin C plus EDTA
                        and DMSO. Is that a teaspoon or a tablespoon, and can liposomal
                        vitamin C be used?
                    </p>

                </div>

                <div class="post">

                    <div class="who">
                        <div class="avatar">RT</div>

                        <div>
                            <b>Rachel T.</b>
                            <span>in general · Aug 15</span>
                        </div>
                    </div>

                    <p>
                        Gathering everyone's takeaways from this month's
                        "Envisioning Freedom" message. What stood out most to you?
                    </p>

                </div>

                <div class="post">

                    <div class="who">
                        <div class="avatar">DW</div>

                        <div>
                            <b>Diane W.</b>
                            <span>in envisioningforlife · Aug 14</span>
                        </div>
                    </div>

                    <p>
                        A space to share what we are each praying through this week.
                        Grateful for this community.
                    </p>

                </div>

            </div>

        </div>

        <p class="disc">
            A fresh community space on your owned platform. Note: past Uscreen
            discussions cannot be exported, so threads start new at launch.
        </p>

    </div>
</section>

@endsection