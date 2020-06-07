<div class="row">

    <div class="col-md-6 mx-auto">

        <div class="card card-body bg-light mt-5">

            <h2>Settings page</h2>

            <p>Change login credentials</p>

            <form action="<?php echo URLROOT; ?>users/settings/<?php echo $data['id']; ?>" method="post">

                <div class="form-group">

                    <label for="email">Current Email: </label>

                    <input type="text" disabled name="email" class="form-control form-control-lg" value="<?php echo $data['email']; ?>">

                </div>

                <div class="form-group">

                    <label for="email">New email: <sup>*</sup></label>

                    <input type="text" name="emailNew" class="form-control form-control-lg <?php echo (!empty($data['email_err'])) ? 'is-invalid': '';?>" value="<?php echo $data['emailNew']; ?>">

                    <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>

                </div>

                <div class="form-group">

                    <label for="password">New Password: <sup>*</sup></label>

                    <input type="password" name="passwordNew" class="form-control form-control-lg <?php echo (!empty($data['password_err'])) ? 'is-invalid': '';?>" value="">

                    <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>

                </div>

                <?php if($_SESSION['user_type'] == 1): ?>

                <div class="form-group">

                    <label class="containerCheckbox">

                       <input type="checkbox" name="admin" <?=($data['type_id'] == 1) ? 'checked' : '' ;?>>  Admin

                        <span class="checkmark"></span>

                    </label>

                </div>

                <?php endif; ?>

                <div class="form-group">

                    <input type="submit" value="Save changes" class="btn btn-success btn-block">

                </div>

            </form>

        </div>

        

    </div>

</div>