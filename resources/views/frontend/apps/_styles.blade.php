{{-- Scoped styles for the /apps hub, /apps/{slug} detail pages and the /tools app promo.
     Everything is namespaced under .zl so it cannot leak into the rest of the site,
     and it does not depend on the compiled Tailwind bundle being rebuilt. --}}
<style>
    .zl{--zl-teal:#2a8c87;--zl-teal-d:#1e6e6a;--zl-teal-50:#eafaf9;--zl-ink:#0f172a;--zl-slate:#64748b;--zl-mut:#94a3b8;--zl-line:#e9edf1;--zl-bg:#fcfdfe;color:var(--zl-ink);}
    .zl *{box-sizing:border-box;}
    .zl-serif{font-family:'DM Serif Display',Georgia,serif;font-weight:400;}
    .zl a{color:inherit;text-decoration:none;}

    .zl-wrap{max-width:1120px;margin:0 auto;padding:0 1rem;}
    .zl-wrap-sm{max-width:920px;margin:0 auto;padding:0 1rem;}

    /* ---- hub hero ---- */
    .zl-hero{max-width:760px;margin:0 auto;padding:9rem 1rem 3.5rem;text-align:center;}
    .zl-eyebrow{font-size:11px;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:var(--zl-teal);margin:0 0 1.25rem;}
    .zl-h1{font-family:'DM Serif Display',Georgia,serif;font-size:clamp(2.25rem,5vw,3.5rem);line-height:1.12;margin:0 0 1.25rem;}
    .zl-h1 em{color:var(--zl-teal);font-style:italic;}
    .zl-lede{color:var(--zl-slate);font-size:1.05rem;line-height:1.7;margin:0;}

    /* ---- app grid ---- */
    .zl-grid{display:grid;grid-template-columns:1fr;gap:1.25rem;}
    @media(min-width:640px){.zl-grid{grid-template-columns:1fr 1fr;}}

    .zl-card{display:flex;flex-direction:column;background:#fff;border:1px solid var(--zl-line);border-radius:24px;padding:1.75rem;transition:border-color .25s,box-shadow .25s,transform .25s;}
    .zl-card:hover{border-color:#dbe3e9;box-shadow:0 20px 50px -18px rgba(15,23,42,.14);transform:translateY(-2px);}
    .zl-card-ico{width:64px;height:64px;border-radius:16px;box-shadow:0 4px 14px -6px rgba(15,23,42,.2);margin-bottom:1.25rem;}
    .zl-card-name{font-family:'DM Serif Display',Georgia,serif;font-size:1.3rem;line-height:1.25;margin:0 0 .5rem;}
    .zl-card-desc{font-size:.9rem;color:var(--zl-slate);line-height:1.65;margin:0;flex:1;}
    .zl-card-foot{display:flex;align-items:center;justify-content:space-between;gap:.75rem;margin-top:1.5rem;padding-top:1.25rem;border-top:1px solid var(--zl-line);}
    .zl-more{font-size:13px;font-weight:600;color:var(--zl-teal);}
    .zl-more:hover{color:var(--zl-teal-d);}
    .zl-badge{height:34px;width:auto;display:block;}
    .zl-badge-lg{height:50px;}

    .zl-note{text-align:center;font-size:.85rem;color:var(--zl-mut);margin:3.5rem 0 0;}

    /* ---- detail: premium hero band ---- */
    .zl-herowrap{background:radial-gradient(circle at 18% 0%,rgba(42,140,135,.10),transparent 55%),radial-gradient(circle at 100% 30%,rgba(42,140,135,.06),transparent 50%),var(--zl-bg);border-bottom:1px solid var(--zl-line);padding-bottom:3rem;}

    /* ---- detail: breadcrumb ---- */
    .zl-crumb{font-size:13px;color:var(--zl-mut);padding-top:8rem;}
    .zl-crumb a:hover{color:var(--zl-teal);}
    .zl-crumb span{margin:0 .4rem;}
    .zl-crumb .cur{color:var(--zl-slate);}

    /* ---- detail: hero ---- */
    .zl-apphero{display:flex;flex-direction:column;gap:1.5rem;margin-top:2rem;}
    @media(min-width:640px){.zl-apphero{flex-direction:row;align-items:flex-start;gap:2.25rem;}}
    .zl-apphero-ico{width:96px;height:96px;border-radius:24px;box-shadow:0 0 0 8px rgba(42,140,135,.08),0 20px 40px -14px rgba(15,23,42,.3);flex:none;}
    .zl-apphero h1{font-family:'DM Serif Display',Georgia,serif;font-size:clamp(2rem,4.2vw,2.75rem);line-height:1.12;margin:0;}
    .zl-apphero p{color:var(--zl-slate);font-size:1.05rem;line-height:1.7;margin:.7rem 0 0;max-width:46ch;}
    .zl-chips{display:flex;flex-wrap:wrap;gap:.4rem;margin-top:1.1rem;}
    .zl-chip{font-size:11px;font-weight:600;background:#f1f5f9;color:#475569;padding:.3rem .65rem;border-radius:999px;}
    .zl-cta-inline{display:inline-block;margin-top:1.5rem;}

    /* ---- detail: trust row ---- */
    .zl-trust{display:flex;flex-wrap:wrap;gap:1.5rem;margin-top:1.5rem;}
    .zl-trust-item{display:flex;align-items:center;gap:.5rem;font-size:.82rem;font-weight:600;color:#334155;}
    .zl-trust-item i{color:var(--zl-teal);font-size:.85rem;}

    /* ---- detail: screenshots (phone-frame) ---- */
    .zl-shots{display:flex;gap:1.5rem;overflow-x:auto;padding:2.5rem 0 1.5rem;scroll-snap-type:x mandatory;-webkit-overflow-scrolling:touch;}
    .zl-phone{position:relative;flex:none;width:210px;height:430px;background:#0b1220;border-radius:34px;padding:8px;box-shadow:0 30px 60px -20px rgba(15,23,42,.35);scroll-snap-align:start;}
    .zl-phone::before{content:'';position:absolute;top:8px;left:50%;transform:translateX(-50%);width:56px;height:6px;border-radius:999px;background:#1e293b;z-index:2;}
    .zl-phone img{width:100%;height:100%;object-fit:cover;border-radius:26px;display:block;}
    .zl-shots::-webkit-scrollbar{height:6px;}
    .zl-shots::-webkit-scrollbar-thumb{background:#e2e8f0;border-radius:999px;}

    /* ---- detail: body layout ---- */
    .zl-cols{display:grid;grid-template-columns:1fr;gap:3rem;margin-top:3.5rem;}
    @media(min-width:900px){.zl-cols{grid-template-columns:2fr 1fr;gap:3.5rem;}}
    .zl-sec{margin-bottom:3.25rem;}
    .zl-sec:last-child{margin-bottom:0;}
    .zl-h2{font-family:'DM Serif Display',Georgia,serif;font-size:1.6rem;margin:0 0 1.1rem;}
    .zl-p{color:var(--zl-slate);line-height:1.75;margin:0;}
    .zl-h3{font-size:1rem;font-weight:600;margin:2rem 0 1.1rem;}

    /* ---- detail: feature cards ---- */
    .zl-featgrid{display:grid;grid-template-columns:1fr;gap:1rem;}
    @media(min-width:560px){.zl-featgrid{grid-template-columns:1fr 1fr;}}
    .zl-featcard{background:#fff;border:1px solid var(--zl-line);border-radius:18px;padding:1.35rem;transition:border-color .2s,box-shadow .2s;}
    .zl-featcard:hover{border-color:#dbe3e9;box-shadow:0 16px 34px -18px rgba(15,23,42,.18);}
    .zl-featcard .ic{width:42px;height:42px;border-radius:12px;background:var(--zl-teal-50);color:var(--zl-teal);display:flex;align-items:center;justify-content:center;font-size:1.05rem;margin-bottom:.9rem;}
    .zl-featcard b{display:block;font-size:.92rem;color:var(--zl-ink);font-weight:600;}
    .zl-featcard span{display:block;font-size:.85rem;color:var(--zl-slate);line-height:1.6;margin-top:.3rem;}

    .zl-faq{border-bottom:1px solid var(--zl-line);padding:1rem 0;}
    .zl-faq summary{display:flex;justify-content:space-between;align-items:center;gap:1rem;cursor:pointer;list-style:none;font-weight:600;color:#1e293b;}
    .zl-faq summary::-webkit-details-marker{display:none;}
    .zl-faq summary .pm{color:var(--zl-mut);transition:transform .2s;font-size:1.1rem;line-height:1;flex:none;}
    .zl-faq[open] summary .pm{transform:rotate(45deg);}
    .zl-faq p{font-size:.9rem;color:var(--zl-slate);line-height:1.7;margin:.85rem 0 0;}

    /* ---- detail: aside ---- */
    .zl-aside > * + *{margin-top:2rem;}
    .zl-panel{background:#fff;border:1px solid var(--zl-line);border-radius:18px;padding:1.5rem;}
    .zl-panel h3{font-size:1rem;font-weight:600;margin:0 0 1.1rem;}
    .zl-dl{margin:0;font-size:.875rem;}
    .zl-dl .row{display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:.5rem 0;border-bottom:1px solid #f1f5f9;}
    .zl-dl .row:last-child{border-bottom:none;}
    .zl-dl dt{color:var(--zl-slate);display:flex;align-items:center;gap:.55rem;}
    .zl-dl dt i{color:var(--zl-teal);font-size:.8rem;width:14px;text-align:center;}
    .zl-dl dd{margin:0;font-weight:600;text-align:right;}
    .zl-privacy{display:inline-block;margin-top:1.1rem;font-size:13px;font-weight:600;color:var(--zl-teal);}

    .zl-rel a{display:flex;align-items:center;gap:.75rem;background:#fff;border:1px solid var(--zl-line);border-radius:16px;padding:.9rem;transition:border-color .2s;}
    .zl-rel a:hover{border-color:#dbe3e9;}
    .zl-rel img{width:44px;height:44px;border-radius:12px;flex:none;}
    .zl-rel span{font-size:.875rem;font-weight:600;line-height:1.3;}
    .zl-rel > * + *{margin-top:.75rem;}

    /* ---- detail: closing CTA ---- */
    .zl-band{background:linear-gradient(135deg,var(--zl-teal),var(--zl-teal-d));border-radius:28px;padding:3.25rem 2rem;text-align:center;margin:5rem 0 2rem;}
    .zl-band h2{font-family:'DM Serif Display',Georgia,serif;font-size:clamp(1.5rem,3vw,2rem);color:#fff;margin:0 0 .6rem;}
    .zl-band p{color:rgba(255,255,255,.85);font-size:.92rem;margin:0 0 1.6rem;}
    .zl-back{display:block;text-align:center;font-size:13px;font-weight:600;color:var(--zl-teal);margin-top:2rem;}

    /* ---- smooth hand-off into the site's dark global footer (#zonely-platform-footer,
       bg-slate-950) so the light .zl page doesn't end in a hard rectangular cut.
       Page-scoped only — the shared footer partial itself is untouched. ---- */
    .zl-footbridge{margin-top:4rem;height:90px;border-radius:40px 40px 0 0;background:linear-gradient(180deg,rgba(2,6,23,0) 0%,#020617 100%);}

    /* ---- detail: sticky mobile install bar ---- */
    .zl-stickybar{position:fixed;left:0;right:0;bottom:0;z-index:200;background:#fff;border-top:1px solid var(--zl-line);box-shadow:0 -8px 24px -12px rgba(15,23,42,.15);padding:.7rem 1rem;display:flex;align-items:center;gap:.75rem;transform:translateY(110%);transition:transform .25s ease;}
    .zl-stickybar.show{transform:translateY(0);}
    .zl-stickybar img.ico{width:36px;height:36px;border-radius:10px;flex:none;}
    .zl-stickybar .nm{font-size:.82rem;font-weight:700;line-height:1.2;}
    .zl-stickybar .sub{font-size:.72rem;color:var(--zl-slate);}
    .zl-stickybar .go{margin-left:auto;background:var(--zl-teal);color:#fff;font-size:.78rem;font-weight:700;padding:.55rem 1rem;border-radius:10px;white-space:nowrap;}
    @media(min-width:900px){.zl-stickybar{left:auto;right:1.5rem;bottom:1.5rem;border-radius:16px;border:1px solid var(--zl-line);max-width:360px;}}
    /* Logged-in users get the site's own mobile bottom nav (fixed, bottom:0, z-50) —
       lift our bar above it on mobile so the two never overlap. Desktop bottom-nav is
       lg:hidden, and our bar switches to a bottom-right corner card there, so no clash. */
    @media(max-width:899px){body.has-bottom-nav .zl-stickybar{bottom:calc(4.5rem + env(safe-area-inset-bottom));}}
</style>
