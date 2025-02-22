<?php
?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="manage_evaluator">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label for="" class="control-label">First Name</label>
							<input type="text" name="firstname" class="form-control form-control-sm" required value="<?php echo isset($firstname) ? $firstname : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Middle Name (optional)</label>
							<input type="text" name="middlename" class="form-control form-control-sm" value="<?php echo isset($middlename) ? $middlename : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Last Name</label>
							<input type="text" name="lastname" class="form-control form-control-sm" required value="<?php echo isset($lastname) ? $lastname : '' ?>">
						</div>
					</div>
					<div class="col-md-6">
						
						<div class="form-group">
							<label class="control-label">Email</label>
							<input type="email" class="form-control form-control-sm" name="email" required value="<?php echo isset($email) ? $email : '' ?>">
							<small id="#msg"></small>
						</div>
						<div class="form-group">
							<label class="control-label">Password</label>
							<input type="password" class="form-control form-control-sm" name="password" <?php echo !isset($id) ? "required":'' ?>>
							<small><i><?php echo isset($id) ? "Leave this blank if you dont want to change you password":'' ?></i></small>
						</div>
						<div class="form-group">
							<label class="label control-label">Confirm Password</label>
							<input type="password" class="form-control form-control-sm" name="cpass" <?php echo !isset($id) ? 'required' : '' ?>>
							<small id="pass_match" data-status=''></small>
						</div>
					</div>
				</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-center d-flex">
					<button class="btn btn-primary mr-2">Save</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=evaluator/evaluator_list'">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>
<style>
	img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>
<script>
    $('[name="password"], [name="cpass"]').keyup(function() {
        var pass = $('[name="password"]').val();
        var cpass = $('[name="cpass"]').val();
        if (cpass === '' || pass === '') {
            $('#pass_match').attr('data-status', '');
        } else {
            if (cpass === pass) {
                $('#pass_match').attr('data-status', '1').html('<i class="text-success">Password Matched.</i>');
            } else {
                $('#pass_match').attr('data-status', '2').html('<i class="text-danger">Password does not match.</i>');
            }
        }
    });

    $('#manage_evaluator').submit(function(e) {
        e.preventDefault();
        $('input').removeClass('border-danger');
        $('.error-message').remove();
        start_load();

        var validEmailDomains = ['@gmail.com', '@outlook.com', '@yahoo.com', '@edu.np'];
        var enteredEmail = $('[name="email"]').val().toLowerCase();
        var isValidDomain = validEmailDomains.some(domain => enteredEmail.endsWith(domain));
        
        var enteredPassword = $('[name="password"]').val();
        var hasUppercase = /[A-Z]/.test(enteredPassword);
        var hasSymbol = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\/\-|=]/.test(enteredPassword);
        var hasNumber = /[0-9]/.test(enteredPassword);
        
        var errorMessages = [];

        if (!isValidDomain) {
            errorMessages.push('Enter a valid email domain. Valid domains: ' + validEmailDomains.join(', '));
            $('[name="email"]').addClass('border-danger');
            $('[name="email"]').after('<div class="error-message text-danger">Enter a valid email domain.</div>');
        }

        if (!hasUppercase || !hasSymbol || !hasNumber) {
            errorMessages.push('Password must contain an uppercase letter, a symbol, and a number.');
            $('[name="password"]').addClass('border-danger');
            $('[name="password"]').after('<div class="error-message text-danger">Password must contain an uppercase letter, a symbol, and a number.</div>');
        }

        if ($('#pass_match').attr('data-status') !== '1' || errorMessages.length > 0) {
            end_load();
            return false;
        }

        $.ajax({
            url: 'ajax.php?action=save_evaluator',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
                if (resp == 1) {
                    alert_toast('Data successfully saved.', 'success');
                    setTimeout(function() {
                        location.replace('index.php?page=evaluator/evaluator_list');
                    }, 750);
                } else if (resp == 2) {
                    $('[name="email"]').addClass('border-danger');
                    $('[name="email"]').after('<div class="error-message text-danger">Email already exists.</div>');
                    end_load();
                }
            }
        });
    });
</script>
