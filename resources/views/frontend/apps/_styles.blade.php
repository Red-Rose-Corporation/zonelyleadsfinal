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
    .zl-badge-lg{height:48px;}

    .zl-note{text-align:center;font-size:.85rem;color:var(--zl-mut);margin:3.5rem 0 0;}

    /* ---- detail: breadcrumb ---- */
    .zl-crumb{font-size:13px;color:var(--zl-mut);padding-top:8rem;}
    .zl-crumb a:hover{color:var(--zl-teal);}
    .zl-crumb span{margin:0 .4rem;}
    .zl-crumb .cur{color:var(--zl-slate);}

    /* ---- detail: hero ---- */
    .zl-apphero{display:flex;flex-direction:column;gap:1.5rem;margin-top:2rem;}
    @media(min-width:640px){.zl-apphero{flex-direction:row;align-items:flex-start;gap:2rem;}}
    .zl-apphero-ico{width:88px;height:88px;border-radius:22px;box-shadow:0 6px 20px -8px rgba(15,23,42,.25);flex:none;}
    .zl-apphero h1{font-family:'DM Serif Display',Georgia,serif;font-size:clamp(1.9rem,4vw,2.5rem);line-height:1.15;margin:0;}
    .zl-apphero p{color:var(--zl-slate);line-height:1.7;margin:.6rem 0 0;}
    .zl-chips{display:flex;flex-wrap:wrap;gap:.4rem;margin-top:1rem;}
    .zl-chip{font-size:11px;font-weight:600;background:#f1f5f9;color:#475569;padding:.3rem .65rem;border-radius:999px;}
    .zl-cta-inline{display:inline-block;margin-top:1.25rem;}

    /* ---- detail: screenshots ---- */
    .zl-shots{display:flex;gap:1rem;overflow-x:auto;padding:.25rem 0 1rem;scroll-snap-type:x mandatory;-webkit-overflow-scrolling:touch;}
    .zl-shots img{height:420px;width:auto;border-radius:18px;border:1px solid #e2e8f0;flex:none;scroll-snap-align:start;}
    .zl-shots::-webkit-scrollbar{height:6px;}
    .zl-shots::-webkit-scrollbar-thumb{background:#e2e8f0;border-radius:999px;}

    /* ---- detail: body layout ---- */
    .zl-cols{display:grid;grid-template-columns:1fr;gap:3rem;margin-top:3.5rem;}
    @media(min-width:900px){.zl-cols{grid-template-columns:2fr 1fr;gap:3.5rem;}}
    .zl-sec{margin-bottom:3.25rem;}
    .zl-sec:last-child{margin-bottom:0;}
    .zl-h2{font-family:'DM Serif Display',Georgia,serif;font-size:1.5rem;margin:0 0 1rem;}
    .zl-p{color:var(--zl-slate);line-height:1.75;margin:0;}
    .zl-h3{font-size:1rem;font-weight:600;margin:2rem 0 1rem;}

    .zl-feat{display:flex;gap:1rem;margin-bottom:1.15rem;}
    .zl-feat-n{width:38px;height:38px;border-radius:12px;background:var(--zl-teal-50);color:var(--zl-teal);display:flex;align-items:center;justify-content:center;font-weight:700;flex:none;}
    .zl-feat b{display:block;font-size:.95rem;color:var(--zl-ink);font-weight:600;}
    .zl-feat span{display:block;font-size:.875rem;color:var(--zl-slate);line-height:1.6;margin-top:.15rem;}

    .zl-faq{border-bottom:1px solid var(--zl-line);padding:1rem 0;}
    .zl-faq summary{display:flex;justify-content:space-between;align-items:center;cursor:pointer;list-style:none;font-weight:600;color:#1e293b;}
    .zl-faq summary::-webkit-details-marker{display:none;}
    .zl-faq summary .pm{color:var(--zl-mut);transition:transform .2s;font-size:1.1rem;line-height:1;}
    .zl-faq[open] summary .pm{transform:rotate(45deg);}
    .zl-faq p{font-size:.9rem;color:var(--zl-slate);line-height:1.7;margin:.85rem 0 0;}

    /* ---- detail: aside ---- */
    .zl-aside > * + *{margin-top:2rem;}
    .zl-panel{background:#fff;border:1px solid var(--zl-line);border-radius:18px;padding:1.5rem;}
    .zl-panel h3{font-size:1rem;font-weight:600;margin:0 0 1rem;}
    .zl-dl{margin:0;font-size:.875rem;}
    .zl-dl .row{display:flex;justify-content:space-between;gap:1rem;padding:.35rem 0;}
    .zl-dl dt{color:var(--zl-mut);}
    .zl-dl dd{margin:0;font-weight:500;text-align:right;}
    .zl-privacy{display:inline-block;margin-top:1rem;font-size:13px;font-weight:600;color:var(--zl-teal);}

    .zl-rel a{display:flex;align-items:center;gap:.75rem;background:#fff;border:1px solid var(--zl-line);border-radius:16px;padding:.9rem;transition:border-color .2s;}
    .zl-rel a:hover{border-color:#dbe3e9;}
    .zl-rel img{width:44px;height:44px;border-radius:12px;flex:none;}
    .zl-rel span{font-size:.875rem;font-weight:600;line-height:1.3;}
    .zl-rel > * + *{margin-top:.75rem;}

    /* ---- detail: closing CTA ---- */
    .zl-band{background:var(--zl-teal);border-radius:24px;padding:3rem 2rem;text-align:center;margin:5rem 0 2rem;}
    .zl-band h2{font-family:'DM Serif Display',Georgia,serif;font-size:clamp(1.4rem,3vw,1.9rem);color:#fff;margin:0 0 .6rem;}
    .zl-band p{color:rgba(255,255,255,.82);font-size:.9rem;margin:0 0 1.4rem;}
    .zl-back{display:block;text-align:center;font-size:13px;font-weight:600;color:var(--zl-teal);margin-top:2rem;}
</style>
