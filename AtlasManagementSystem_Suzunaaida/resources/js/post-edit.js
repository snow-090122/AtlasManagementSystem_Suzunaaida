document.addEventListener("DOMContentLoaded", function () {
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

  document.querySelectorAll(".edit-modal-open").forEach((button) => {
    button.addEventListener("click", function () {
      document.getElementById("edit-post-id").value = this.getAttribute("post_id");
      document.getElementById("edit-post-title").value = this.getAttribute("post_title");
      document.getElementById("edit-post-body").value = this.getAttribute("post_body");
      document.querySelector(".js-modal").classList.add("open");
    });
  });

  document.querySelectorAll(".js-modal-close").forEach((button) => {
    button.addEventListener("click", function () {
      document.querySelector(".js-modal").classList.remove("open");
    });
  });

  document.getElementById("edit-post-form").addEventListener("submit", function (event) {
    event.preventDefault();
    const postId = document.getElementById("edit-post-id").value;
    const postTitle = document.getElementById("edit-post-title").value;
    const postBody = document.getElementById("edit-post-body").value;

    fetch(`/bulletin_board/update/${postId}`, {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": csrfToken,
      },
      body: JSON.stringify({ post_title: postTitle, post_body: postBody }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          document.querySelector(".detsail_post_title").textContent = postTitle;
          document.querySelector(".detsail_post").textContent = postBody;
          document.querySelector(".js-modal").classList.remove("open");
        } else {
          alert("更新に失敗しました");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("エラーが発生しました");
      });
  });
});
