jQuery(document).ready(function ($) {

    // Check if the .scrollSlider elements exist before executing the script

    $('.scrollSlider').each(function () {

        const slider = $(this); // Each individual .scrollSlider element

        let isDown = false;

        let startX;

        let scrollLeft;



        // Only scroll if content is overflowing

        function canScrollHorizontally() {

            const el = slider[0];

            return el.scrollWidth > el.clientWidth;

        }



        // Drag to scroll

        slider.on('mousedown', function (e) {

            if (!canScrollHorizontally()) return;



            isDown = true;

            slider.addClass('dragging');

            startX = e.pageX - slider.offset().left;

            scrollLeft = slider.scrollLeft();

            e.preventDefault();

        });



        slider.on('mouseleave mouseup', function () {

            isDown = false;

            slider.removeClass('dragging');

        });



        slider.on('mousemove', function (e) {

            if (!isDown || !canScrollHorizontally()) return;



            const x = e.pageX - slider.offset().left;

            const walk = (x - startX) * 1.5; // scroll speed

            slider.scrollLeft(scrollLeft - walk);

        });



        // Scroll with mouse wheel

        slider.on('wheel', function (e) {

            const el = slider[0];

            if (!canScrollHorizontally()) return;



            const maxScroll = el.scrollWidth - el.clientWidth;

            const newScroll = el.scrollLeft + e.originalEvent.deltaY;



            // Only scroll if within bounds

            if (newScroll >= 0 && newScroll <= maxScroll) {

                e.preventDefault(); // prevent default page scroll

                el.scrollLeft = newScroll;

            }

        });

    });
    //video
    $(document).on("click", ".video-play", function () {


        const $wrapper = $(this).closest('.video-player');
        if (!$wrapper.length) return;

        // ✅ jQuery way to read data attribute
        let videoSrc = $wrapper.data('video');
        if (!videoSrc) return;
        console.log(videoSrc);
        const src =
            videoSrc +
            '?autoplay=1&mute=1&rel=0&playsinline=1&enablejsapi=1';

        // 🔑 FORCE EMBED FORMAT
        if (src.includes('watch?v=')) {
            src = src.replace('watch?v=', 'embed/');
        }

        const iframe = document.createElement('iframe');

        iframe.src =
            src +
            '?autoplay=1&mute=1&playsinline=1&rel=0&modestbranding=1';

        iframe.allow = 'autoplay; encrypted-media; picture-in-picture';
        iframe.allowFullscreen = true;
        iframe.setAttribute('frameborder', '0');
        iframe.className = 'inset-0 w-full h-full';

        // IMPORTANT: append first, THEN replace HTML
        $wrapper.innerHTML = '';
        $wrapper.empty().append(iframe);
    });

    //video
});