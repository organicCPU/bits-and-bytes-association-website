<?php

if(!defined("baseLoaded"))
{
    header("Location: index.php");
}

require_once "lib/config.php";
require_once $_SERVER['BBA_ROOT'] . "/auth.php";

function generateAlert($status)
{
    printAlertFromStatus($status);

    $_SESSION["status_code"] = null;
}

function generateUserLoginDropdown()
{
    ?>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <?php
    if(isset($_SESSION['login_user']))
    {
        ?>
                <?=$_SESSION['login_user']?>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">aPanel</a>
                    <a class="dropdown-item" href="#">uPanel</a>
                    <div class="dropdown-divider">
                    </div>
                    <a class="dropdown-item" href="login.php?action=logout">Log Out</a>
                </div>
            </div>
        <?php
    }
    else
    {
        ?>
                <?="Guest"?>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <button class="dropdown-item" data-toggle="modal" data-target="#loginModal">Login</button>
                    <a class="dropdown-item" href="register.php">Register</a>
                </div>
            </div>
        <?php
    }
}

//login('MikalMirkas', 'lmao');

?>
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#" aria-label="Bits & Bytes Association">
            <img class="logo" src="./assets/img/banners/bba-banner-black.png" alt="Bits & Bytes Association">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
            </span>
        </button>
        <!-- Modal -->
        <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                        <h5 class="modal-title" id="loginModalLabel">Login</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <form action="login.php?action=login" method="POST">
                <div class="modal-body">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend form-group">
                                <span class="input-group-text" id="login-username">Username:</span>
                            </div>
                            <input type="text" class="form-control" name="username" id="usr" placeholder="Username" aria-label="Username" aria-describedby=""> <!--Fix the formatting of this.-->
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend form-group">
                                <span class="input-group-text" id="login-password">Password:</span>
                            </div>
                            <input type="password" class="form-control" name="field" placeholder="Password" aria-label="Password" aria-describedby="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Login</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </form>
                </div>
            </div>
        </form>
        </div>
        
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">
                        Home 
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        About Us
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">Our Story</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Current Board</a>
                        <a class="dropdown-item" href="#">Past Executives</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Projects
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Create Your Own</a>
                    </div>
                </li>
            </ul>
            <?=generateUserLoginDropdown()?>
        </div>
    </nav>
</header>
<?php if (isset($_SESSION["status_code"])) : ?>
<?=generateAlert($_SESSION["status_code"])?>
<?php endif?>