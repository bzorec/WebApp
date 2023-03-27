<?php
include_once('header_view.php');

function get_ads(): array
{
    global $conn;
    $query = "SELECT ads.*, categories.name AS category_name FROM ads
              JOIN categories ON ads.category_id = categories.id
              ORDER BY ads.publish_date DESC;";
    $res = $conn->query($query);
    $ads = array();
    while ($ad = $res->fetch_object()) {
        $ads[] = $ad;
    }
    return $ads;
}

$ads = get_ads();

foreach ($ads as $ad) {
    ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <a href="/index.php?page=ad-details&id=<?php echo $ad->id; ?>">
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($ad->image); ?>" alt="ad"
                                     class="img-fluid rounded-start" style="max-height: 250px;">
                            </a>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $ad->title; ?></h5>
                                <p class="card-text"><?php echo $ad->category_name; ?></p>
                                <a href="/index.php?page=home&action=ad_details&id=<?php echo $ad->id; ?>"
                                   class="btn btn-primary">Preberi
                                    več</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mx-auto">
                            <div class="comments-section">
                                <h4>
                                    Comments
                                    <button type="button" class="btn btn-link float-end text-white"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#comment-list<?php echo $ad->id; ?>" aria-expanded="false"
                                            aria-controls="comment-list<?php echo $ad->id; ?>">
                                        <i class="bi bi-chevron-down"></i>
                                        <i class="bi bi-chevron-up d-none"></i>
                                    </button>
                                </h4>
                                <div class="collapse" id="comment-list<?php echo $ad->id; ?>">
                                    <ul id="comment-list-ul-<?php echo $ad->id; ?>">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        $(document).ready(function () {
            $('#comment-list<?php echo $ad->id; ?>').on('show.bs.collapse', function () {
                $('.bi-chevron-down').addClass('d-none');
                $('.bi-chevron-up').removeClass('d-none');

                $.ajax({
                    url: `API/comment_api.php?ad_id=<?php echo $ad->id; ?>&limit=5`,
                    type: 'GET',
                    dataType: 'json',
                    success: function (comments) {
                        const commentList = $('#comment-list-ul-<?php echo $ad->id; ?>');
                        if (comments.length > 0) {
                            commentList.empty();
                            for (var i = 0; i < comments.length; i++) {
                                var comment = comments[i];
                                var commentHtml = `
                <li class="comment-item">
                    <div class="comment-header">
                        <span class="comment-author">${comment.user}</span>
                        <span class="comment-date">${comment.created_at}</span>
                        <span class="comment-country">(${comment.country})</span>
                    </div>
                    <div class="comment-content">
                        <p>${comment.content}</p>
                    </div>
                </li>
            `;
                                commentList.append(commentHtml);
                            }
                        } else {
                            commentList.append('<li>No comments yet.</li>');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Failed to retrieve comments: ' + errorThrown);
                    }
                });

            });

            $('#comment-list<?php echo $ad->id; ?>').on('hide.bs.collapse', function () {
                $('.bi-chevron-down').removeClass('d-none');
                $('.bi-chevron-up').addClass('d-none');
            });
        });
    </script>
    <?php
}

include_once('footer_view.php');
?>