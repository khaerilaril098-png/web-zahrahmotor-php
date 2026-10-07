/* =========================================
   ARIL MOTORS
   JQUERY SCRIPT
========================================= */

$(document).ready(function () {


    /* =========================================
       MOBILE MENU
    ========================================= */

    $("#menuToggle").click(function () {

        $("#navMenu").toggleClass("active");

    });


    /* Menutup menu ketika menu diklik */

    $(".nav-menu a").click(function () {

        $("#navMenu").removeClass("active");

    });


    /* =========================================
       SMOOTH SCROLL
    ========================================= */

    $('a[href^="#"]').on("click", function (event) {

        var target = $(this).attr("href");

        if (target !== "#" && $(target).length) {

            event.preventDefault();

            $("html, body").animate(
                {
                    scrollTop: $(target).offset().top - 70
                },
                800
            );

        }

    });


    /* =========================================
       FILTER KENDARAAN
    ========================================= */

    $(".filter-btn").click(function () {

        var filter = $(this).data("filter");

        $(".filter-btn").removeClass("active");

        $(this).addClass("active");


        var visibleCount = 0;


        $(".vehicle-card").each(function () {

            var category = $(this).data("category");


            if (
                filter === "all" ||
                category === filter
            ) {

                $(this)
                    .stop(true, true)
                    .fadeIn(300);

                visibleCount++;

            } else {

                $(this)
                    .stop(true, true)
                    .fadeOut(200);

            }

        });


        if (visibleCount === 0) {

            $("#noResult").fadeIn(300);

        } else {

            $("#noResult").hide();

        }

    });


    /* =========================================
       SEARCH KENDARAAN
    ========================================= */

    $("#searchInput").on("keyup", function () {

        var searchValue = $(this)
            .val()
            .toLowerCase()
            .trim();


        var visibleCount = 0;


        $(".vehicle-card").each(function () {

            var vehicleName = $(this)
                .data("name")
                .toLowerCase();


            if (vehicleName.indexOf(searchValue) !== -1) {

                $(this)
                    .stop(true, true)
                    .fadeIn(250);

                visibleCount++;

            } else {

                $(this)
                    .stop(true, true)
                    .fadeOut(200);

            }

        });


        if (visibleCount === 0) {

            $("#noResult").fadeIn(300);

        } else {

            $("#noResult").hide();

        }

    });


/* =========================================
   DETAIL KENDARAAN + PHOTO SLIDER
========================================= */

var currentImages = [];
var currentImageIndex = 0;


/* Buka Detail Kendaraan */
$(".detail-btn").click(function () {

    var name = $(this).data("name");
    var price = $(this).data("price");
    var year = $(this).data("year");
    var engine = $(this).data("engine");
    var transmission = $(this).data("transmission");
    var color = $(this).data("color");

    currentImages = [
    $(this).data("image1"),
    $(this).data("image2"),
    $(this).data("image3"),
    $(this).data("image4")
].filter(function (image) {
    return image && image !== "";
});

/* Hapus foto yang sama */
currentImages = [...new Set(currentImages)];
    currentImageIndex = 0;

    /* Masukkan data kendaraan */
    $("#modalName").text(name);
    $("#modalPrice").text(price);
    $("#modalYear").text(year);
    $("#modalEngine").text(engine);
    $("#modalTransmission").text(transmission);
    $("#modalColor").text(color);

    /* Tampilkan foto pertama */
    showGalleryImage();

    /* Tampilkan modal */
    $("#vehicleModal")
        .addClass("show")
        .hide()
        .fadeIn(300);

    $("body").css("overflow", "hidden");
});


/* =========================================
   FUNGSI MENAMPILKAN FOTO
========================================= */

function showGalleryImage() {

    $("#modalImage")
        .stop(true, true)
        .fadeOut(150, function () {

            $(this)
                .attr("src", currentImages[currentImageIndex])
                .fadeIn(200);

        });

    /* Update thumbnail */
    $(".thumbnail").removeClass("active");

    $('.thumbnail[data-index="' + currentImageIndex + '"]')
        .addClass("active");

}


/* =========================================
   TOMBOL FOTO SEBELUMNYA
========================================= */

$("#galleryPrev").click(function () {

    currentImageIndex--;

    if (currentImageIndex < 0) {
        currentImageIndex = currentImages.length - 1;
    }

    showGalleryImage();
});


/* =========================================
   TOMBOL FOTO BERIKUTNYA
========================================= */

$("#galleryNext").click(function () {

    currentImageIndex++;

    if (currentImageIndex >= currentImages.length) {
        currentImageIndex = 0;
    }

    showGalleryImage();
});


/* =========================================
   KLIK THUMBNAIL
========================================= */

$(".thumbnail").click(function () {

    currentImageIndex = parseInt(
        $(this).data("index")
    );

    showGalleryImage();

});


    /* =========================================
       CLOSE MODAL
    ========================================= */

    $("#modalClose, .modal-overlay").click(function () {

        $("#vehicleModal").fadeOut(250);

        $("body").css(
            "overflow",
            "auto"
        );

    });


    /* =========================================
       ESC KEY
    ========================================= */

    $(document).keydown(function (event) {

        if (
            event.key === "Escape" &&
            $("#vehicleModal").hasClass("show")
        ) {

            $("#vehicleModal").fadeOut(250);

            $("body").css(
                "overflow",
                "auto"
            );

        }

    });


    /* =========================================
       MODAL CONTACT
    ========================================= */

    $("#modalContact").click(function () {

    var vehicleName = $("#modalName").text();
    var vehiclePrice = $("#modalPrice").text();

    var message =
        "Asslamualaikum Admin ZAHRAH MOTOR,%0A%0A" +
        "Saya tertarik dengan kendaraan:%0A" +
        "Nama: " + vehicleName + "%0A" +
        "Harga: " + vehiclePrice + "%0A%0A" +
        "Mohon informasi lebih lanjut. Terima kasih.";

    var whatsappURL =
        "https://wa.me/6282225487347?text=" + message;

    window.open(
        whatsappURL,
        "_blank"
    );

});


    /* =========================================
       CONTACT FORM
    ========================================= */

$("#contactForm").submit(function (event) {

    event.preventDefault();

    var nama = $("#name").val().trim();
    var telepon = $("#phone").val().trim();
    var email = $("#email").val().trim();
    var kendaraan = $("#vehicle").val();
    var pesan = $("#message").val().trim();

    // Cek data
    if (
        nama === "" ||
        telepon === "" ||
        email === "" ||
        pesan === ""
    ) {
        alert("Mohon lengkapi semua data terlebih dahulu.");
        return;
    }

    // Membuat pesan WhatsApp
    var whatsappMessage =
        "Assalamualaikum ZAHRAH MOTOR,%0A%0A" +
        "*DATA PELANGGAN*%0A" +
        "Nama: " + encodeURIComponent(nama) + "%0A" +
        "No. HP: " + encodeURIComponent(telepon) + "%0A" +
        "Email: " + encodeURIComponent(email) + "%0A" +
        "Kendaraan: " + encodeURIComponent(kendaraan) + "%0A%0A" +
        "*Pesan:*%0A" +
        encodeURIComponent(pesan);

    var whatsappNumber = "6282353231630";

    // Link WhatsApp
    var whatsappURL =
        "https://wa.me/" +
        whatsappNumber +
        "?text=" +
        whatsappMessage;

    // Buka WhatsApp
    window.open(whatsappURL, "_blank");

});

    /* =========================================
       BACK TO TOP
    ========================================= */

    $(window).scroll(function () {

        if ($(this).scrollTop() > 500) {

            $("#backToTop")
                .stop(true, true)
                .fadeIn(300)
                .css("display", "flex");

        } else {

            $("#backToTop")
                .stop(true, true)
                .fadeOut(300);

        }

    });


    $("#backToTop").click(function () {

        $("html, body").animate(
            {
                scrollTop: 0
            },
            800
        );

    });


    /* =========================================
       SCROLL REVEAL SEDERHANA
    ========================================= */

    function revealOnScroll() {

        $(".vehicle-card, .about-content, .about-image, .contact-info, .contact-form-wrapper")
            .each(function () {

                var elementTop = $(this).offset().top;

                var windowBottom =
                    $(window).scrollTop() +
                    $(window).height();


                if (windowBottom > elementTop + 80) {

                    $(this).css({
                        opacity: "1",
                        transform: "translateY(0)"
                    });

                }

            });

    }


    $(".vehicle-card, .about-content, .about-image, .contact-info, .contact-form-wrapper")
        .css({
            opacity: "0",
            transform: "translateY(30px)",
            transition: "all 0.7s ease"
        });


    $(window).on(
        "scroll",
        revealOnScroll
    );


    revealOnScroll();


});