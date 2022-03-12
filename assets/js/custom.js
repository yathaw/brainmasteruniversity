
$(document).ready(function() {

    $('#sampleTable').DataTable();

    $('.select2').select2({
        width: '100%',
        theme: 'bootstrap5',
        placeholder: 'Please select at least one option',
    });

    $('.logoutLink').on('click', function(e) { //Don't foget to change the id form
        Swal.fire({
            title: 'Log out',
            text: 'Are you sure you would like to log out? You will be returned to the login screen.',
            icon: "warning",
            showCancelButton:true,
            confirmButtonText: 'Logout',
            cancelButtonText: 'Cancel',  
            confirmButtonColor: '#d33',
            cancelButtonColor: '#808080',
            reverseButtons: true,

            dangerMode: true}).then((willDelete)=>{
                console.log(willDelete);
                console.log(willDelete.isConfirmed);

                if (willDelete.isConfirmed != false) 
                {
                    window.location='logout.php';
                }
                else
                {
                    Swal.fire({
                        icon: "info",
                        text: "We are so glad you're here!",
                        showConfirmButton: false,
                        timer : 1500,
                        allowOutsideClick: false
                    });
                    
                }
            })
    });

    $('.click').on('click', function(e) { //Don't foget to change the id form
        var timerDiv = `<div class="timer">
        <h3>TOO MANY FAILED ATTEMPTS</h3>
        <div class="timer--clock">
            <div class="minutes-group clock-display-grp">
                <div class="first number-grp">
                    <div class="number-grp-wrp">
                        <div class="num num-0">
                            <p>0</p>
                        </div>
                        <div class="num num-1">
                            <p>1</p>
                        </div>
                        <div class="num num-2">
                            <p>2</p>
                        </div>
                        <div class="num num-3">
                            <p>3</p>
                        </div>
                        <div class="num num-4">
                            <p>4</p>
                        </div>
                        <div class="num num-5">
                            <p>5</p>
                        </div>
                        <div class="num num-6">
                            <p>6</p>
                        </div>
                        <div class="num num-7">
                            <p>7</p>
                        </div>
                        <div class="num num-8">
                            <p>8</p>
                        </div>
                        <div class="num num-9">
                            <p>9</p>
                        </div>
                    </div>
                </div>
                <div class="second number-grp">
                    <div class="number-grp-wrp">
                        <div class="num num-0">
                            <p>0</p>
                        </div>
                        <div class="num num-1">
                            <p>1</p>
                        </div>
                        <div class="num num-2">
                            <p>2</p>
                        </div>
                        <div class="num num-3">
                            <p>3</p>
                        </div>
                        <div class="num num-4">
                            <p>4</p>
                        </div>
                        <div class="num num-5">
                            <p>5</p>
                        </div>
                        <div class="num num-6">
                            <p>6</p>
                        </div>
                        <div class="num num-7">
                            <p>7</p>
                        </div>
                        <div class="num num-8">
                            <p>8</p>
                        </div>
                        <div class="num num-9">
                            <p>9</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clock-separator">
                <p>:</p>
            </div>
            <div class="seconds-group clock-display-grp">
                <div class="first number-grp">
                    <div class="number-grp-wrp">
                        <div class="num num-0">
                            <p>0</p>
                        </div>
                        <div class="num num-1">
                            <p>1</p>
                        </div>
                        <div class="num num-2">
                            <p>2</p>
                        </div>
                        <div class="num num-3">
                            <p>3</p>
                        </div>
                        <div class="num num-4">
                            <p>4</p>
                        </div>
                        <div class="num num-5">
                            <p>5</p>
                        </div>
                        <div class="num num-6">
                            <p>6</p>
                        </div>
                        <div class="num num-7">
                            <p>7</p>
                        </div>
                        <div class="num num-8">
                            <p>8</p>
                        </div>
                        <div class="num num-9">
                            <p>9</p>
                        </div>
                    </div>
                </div>
                <div class="second number-grp">
                    <div class="number-grp-wrp">
                        <div class="num num-0">
                            <p>0</p>
                        </div>
                        <div class="num num-1">
                            <p>1</p>
                        </div>
                        <div class="num num-2">
                            <p>2</p>
                        </div>
                        <div class="num num-3">
                            <p>3</p>
                        </div>
                        <div class="num num-4">
                            <p>4</p>
                        </div>
                        <div class="num num-5">
                            <p>5</p>
                        </div>
                        <div class="num num-6">
                            <p>6</p>
                        </div>
                        <div class="num num-7">
                            <p>7</p>
                        </div>
                        <div class="num num-8">
                            <p>8</p>
                        </div>
                        <div class="num num-9">
                            <p>9</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h4>PLEASE WAIT AND TRY AGAIN LATER.</h4>
    </div>`; 

        Swal.fire({
            html: timerDiv,
            customClass: 'swal-height',
            // showConfirmButton: false,
            // timer: 2000,
            timerProgressBar: true,
            // allowOutsideClick: false
        })
    });


});