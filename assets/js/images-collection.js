$(document).ready(function () {
  $('#add-images-btn').click(function () {
    const index = +$('#widgets-counter').val();
    const template = $('#annonce_images')
      .data('prototype')
      .replace(/__name__/g, index);

    $('#annonce_images').append(template);
    $('#widgets-counter').val(index + 1);

    deleteImageBtn();
  });

  // Delete a specific .form-group block
  const deleteImageBtn = () => {
    $("button[data-action='delete']").click(function () {
      const target = $(this).data('target');
      console.log(target);
      $(target).remove();
    });
  }

  const updateCounter = () => {
    const formGroupCounter = $('#annonce_images .form-group').length;
    $('#widgets-counter').val(formGroupCounter);
  };
  
  deleteImageBtn();

  updateCounter();
});
