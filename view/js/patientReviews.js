// pickStar(uid, val) — called by onclick on each star span
function pickStar(uid, val) {
    var hidden = document.getElementById("hidden_" + uid);
    var row    = document.getElementById("row_" + uid);
    var err    = document.getElementById("err_" + uid);

    if (!hidden || !row) { return; }

    // Store the value
    hidden.value = val;

    // Light up stars
    var stars = row.querySelectorAll("span");
    for (var i = 0; i < stars.length; i++) {
        if (parseInt(stars[i].getAttribute("data-v")) <= val) {
            stars[i].classList.add("lit");
        } else {
            stars[i].classList.remove("lit");
        }
    }

    // Clear error
    if (err) { err.innerHTML = ""; }
}

// Hover preview
document.addEventListener("DOMContentLoaded", function () {
    var rows = document.querySelectorAll(".star-row");
    for (var r = 0; r < rows.length; r++) {
        (function (row) {
            var stars = row.querySelectorAll("span");
            var uid   = row.id.replace("row_", "");
            for (var i = 0; i < stars.length; i++) {
                (function (star) {
                    star.addEventListener("mouseover", function () {
                        var v = parseInt(star.getAttribute("data-v"));
                        for (var j = 0; j < stars.length; j++) {
                            if (parseInt(stars[j].getAttribute("data-v")) <= v) {
                                stars[j].style.color = "#f59e0b";
                            } else {
                                stars[j].style.color = "#ccc";
                            }
                        }
                    });
                    star.addEventListener("mouseout", function () {
                        var hidden  = document.getElementById("hidden_" + uid);
                        var current = hidden ? parseInt(hidden.value) : 0;
                        for (var j = 0; j < stars.length; j++) {
                            if (parseInt(stars[j].getAttribute("data-v")) <= current) {
                                stars[j].style.color = "#f59e0b";
                            } else {
                                stars[j].style.color = "#ccc";
                            }
                        }
                    });
                })(stars[i]);
            }
        })(rows[r]);
    }
});

// Validate star selected before submit
function checkStars(uid) {
    var hidden = document.getElementById("hidden_" + uid);
    var err    = document.getElementById("err_" + uid);
    if (!hidden || parseInt(hidden.value) < 1) {
        if (err) { err.innerHTML = "Please click a star to rate before submitting."; }
        return false;
    }
    return true;
}

// Edit modal
function openEdit(reviewId, rating, reviewText) {
    document.getElementById("edit_review_id").value    = reviewId;
    document.getElementById("edit_review_text").value  = reviewText;
    pickStar("edit_modal", rating);
    document.getElementById("editModal").classList.add("open");
}

function closeEdit() {
    document.getElementById("editModal").classList.remove("open");
}

// Close modal on background click
window.addEventListener("click", function (e) {
    var modal = document.getElementById("editModal");
    if (modal && e.target === modal) { closeEdit(); }
});