<div class="row">

    <div class="col-md-6 mx-auto">

        <div class="card card-body bg-light mt-5">

            <?php flash('register_msg'); 

            flash('settingsErr_msg');

            flash('adminErr_msg'); 

            ?>

            <h2>Login</h2>

            <p>Pleaser fill out the form to login</p>

            <form action=<?= URLROOT."users/login";?> method="post">

                <div class="form-group">

                    <label for="email">Email: <sup>*</sup></label>

                    <input type="text" name="email" class="form-control form-control-lg <?php echo (!empty($data['email_err'])) ? 'is-invalid': '';?>" value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>">

                    <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>

                </div>

                <div class="form-group">

                    <label for="password">Password: <sup>*</sup></label>

                    <input type="password" name="password" class="form-control form-control-lg <?php echo (!empty($data['password_err'])) ? 'is-invalid': '';?>" value="<?php echo isset($data['password']) ? $data['password'] : ''; ?>">

                    <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>

                </div>

                <div class="row">

                    <div class="col">

                        <input type="submit" value="Login" class="btn btn-success btn-block">

                    </div>

                    <div class="col">

                        <a href="<?php echo URLROOT; ?>users/register" class="btn btn-light btn-block">Don't have an account? Register</a>

                    </div>

                </div>

            </form>

        </div>

        

    </div>

</div>