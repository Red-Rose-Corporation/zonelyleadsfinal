{{-- One shared full-screen viewer for every service's photos. Rendered once, only if some service has photos. --}}
<div id="svpLb" class="svp-lb" role="dialog" aria-modal="true" aria-label="Service photos">
    <div class="svp-lb-top">
        <div><b id="svpLbT"></b><span id="svpLbC"></span></div>
        <button type="button" class="svp-lb-x" id="svpLbX" onclick="svpClose()" aria-label="Close photos"><i class="fas fa-xmark"></i></button>
    </div>
    <button type="button" class="svp-lb-ar svp-lb-pv" onclick="svpStep(-1)" aria-label="Previous photo"><i class="fas fa-chevron-left"></i></button>
    <div class="svp-lb-img"><img id="svpLbI" src="" alt=""></div>
    <button type="button" class="svp-lb-ar svp-lb-nx" onclick="svpStep(1)" aria-label="Next photo"><i class="fas fa-chevron-right"></i></button>
    <div class="svp-lb-ts" id="svpLbTs"></div>
</div>
