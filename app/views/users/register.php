<div class="row">

    <div class="col-md-6 mx-auto">

        <div class="card card-body bg-light mt-5">

            <h2>Create An Account</h2>

            <p>Please fill out the form to register</p>

            <form id="register" action="<?php echo URLROOT; ?>users/register" method="post">

            <div class="form-group">

                    <label for="firstName">First name: <sup>*</sup></label>

                    <input type="text" name="firstName" class="form-control form-control-lg" placeholder="John">

                    <span class="firstName text-danger"></span>

                </div>

                <div class="form-group">

                    <label for="lastName">Last name: <sup>*</sup></label>

                    <input type="text" name="lastName" class="form-control form-control-lg" placeholder="Johnson">

                    <span class="lastName text-danger"></span>

                </div>

                <div class="form-group">

                    <label for="email">Email: <sup>*</sup></label>

                    <input type="text" name="email" class="form-control form-control-lg" placeholder="your_email123@gmail.com">

                    <span class="email text-danger"></span>

                </div>

                <div class="form-group">

                    <label for="password">Password: <sup>*</sup></label>

                    <input type="password" name="password" class="form-control form-control-lg" placeholder="At least 6 characters">

                    <span class="password text-danger"></span>

                </div>

                <div class="form-group">

                    <label for="confPasswd">Confirm password: <sup>*</sup></label>

                    <input type="password" name="confPasswd" class="form-control form-control-lg" placeholder="Confirm password">

                    <span class="confPasswd text-danger"></span>

                </div>

                <div class="row mt-2">

                    <div class="col">

                        <input type="button" id="regUser" value="Register" class="btn btn-success btn-block send">

                    </div>

                    <div class="col">

                        <a href="<?php echo URLROOT; ?>users/login" class="btn btn-light btn-block">Have an account? Login</a>

                    </div>

                </div>

            </form>

        </div>

        

    </div>

</div>