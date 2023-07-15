
// $("#form-export").on('submit', function(e){

//   $(".submit-buttom").addClass('disabled');
//   e.preventDefault();

//   const data = $(this).serialize();
//   const url = $(this).attr('action');

//   ajaxs( data, url , {
//     success: (data) => {
//       const content = "data:application/zip;base64," + data.content;
//       download(content, data.name, data.format  );
//     },
//     complete : () => $(".submit-buttom").removeClass('disabled')
//   })  

//   return false;

// })