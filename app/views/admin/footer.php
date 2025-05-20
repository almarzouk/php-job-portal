</div> <!-- .main -->
</div> <!-- .content -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="toastMessage" class="toast align-items-center text-white bg-success border-0" role="alert"
    aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        <!-- سيتم تعبئة الرسالة هنا -->
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
        aria-label="Close"></button>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
  $('.delete-btn').on('click', function(e) {
    e.preventDefault();
    const jobId = $(this).data('id');
    $('#confirmDeleteBtn').attr('href', '/public/admin/deleteJob/' + jobId);
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
  });
});
// Delete User Modal
$(document).ready(function() {
  $('.delete-user-btn').on('click', function(e) {
    e.preventDefault();
    const userId = $(this).data('id');
    $('#confirmDeleteBtn').attr('href', '/public/admin/deleteUser/' + userId);
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
  });
});
</script>
<script>
tinymce.init({
  selector: '#description',
  menubar: false,
  plugins: 'lists link image code',
  toolbar: 'undo redo | bold italic | bullist numlist | link image | code',
  forced_root_block: 'p', // استخدم p ولكن يمكن تغييره
  force_br_newlines: false,
  force_p_newlines: true,
  convert_newlines_to_brs: false,
  valid_elements: '*[*]' // للسماح بكل العناصر عند اللزوم
});
</script>
</body>

</html>