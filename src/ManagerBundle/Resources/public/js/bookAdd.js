// $(document).ready(function(){
//
//     $( ".target" ).click(function() {
//         var select = $("option:selected", this);
//         var status = select[0].label;
//         var id = $(this).attr("name");
//             $.ajax({
//                 type: 'GET',
//                 url: "{{ path('ajax_change')}}",
//                 data: {status: status, id: id},
//                 dataType: 'json',
//                 success: function (data) {
//
//                 }
//             });
//         // });
//         //
//     });
// });