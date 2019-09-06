// alert('Hello from script.js');
/**
 * Send links of class "delete" via post after a confirmation dialog
 */
$("a.delete").on("click", function(e) {

    e.preventDefault();

    if (confirm("Are you sure?")) {

        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('action', $(this).attr('href'));
        frm.appendTo("body");
        frm.submit();

    }
});

/**
 * Add a method to validate a date time string
 */
$.validator.addMethod("dateTime", function(value, element){
    return (value=="") || !isNaN(Date.parse(value));
}, "Must be a valid date and time");

/**
 * Validate the champion form
 */
$("#formChampion").validate({
    rules: {
        title: {
            required: true
        },
        content: {
            required: true
        },
        // published_at: {
        //     dateTime: true
        // }
    }
});

/**
 * Handle the publish button for publishing champions
 */
$("button.publish").on("click", function (){

    var id = $(this).data('id');
    var button = $(this);
    //alert(id);

    $.ajax({
        url: '/admin/publish-champion.php',
        type: 'POST',
        data: {id: id}
    })
    .done(function(data) {

        button.parent().html(data);

    })
    .fail(function(data) {

        alert("An error occurred");

    });
});

/** 
 * javascript comment 
 * show the date and time picker for the published at field 
 */
// $('#published_at').datetimepicker({
//     format:'Y-m-d'
// });

/**
 * Validate the contact form
 */
$("#formContact").validate({
    rules: {
		email: {
			required: true,
			email: true
		},
		subject: {
			required: true
		},
		message: {
			required: true
		}
    }
});