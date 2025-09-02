<?php

require_once 'session.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);


//Handle Add New Note Ajax Request
if(isset($_POST['action']) && $_POST['action'] == 'add_note'){
    $title=$curser->test_input($_POST['title']);
    $note=$curser->test_input($_POST['note']);

    $curser->add_new_note($cid, $title, $note);
    $curser->notification($cid, "admin", 'Note added');

}

//Handle Display All Notes of An User
if(isset($_POST['action']) && $_POST['action'] == 'display_notes'){
    $output='';

    $notes=$curser->get_notes($cid);

    if($notes){
        $output .= '<table class="table table-striped table-sm text-center">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Note</th>
                <th>Action</th>
            </tr>
            </thead>
                <tbody>';
                foreach($notes as $row){
                    $output .='<tr>
                <td>'.$row['id'].'</td>
                <td>'.$row['title'].'</td>
                <td>'.substr($row['note'],0,75).'...</td>
                <td>
                    <a href="#" id="'.$row['id'].'" title="View Details" class="text-success infoBtn">
                        <i class="fas fa-info-circle fa-lg"></i>
                    </a>&nbsp;
                    <a href="#" id="'.$row['id'].'" title="Edit Note" class="text-primary editBtn">
                        <i class="fas fa-edit fa-lg" data-toggle="modal" data-target="#editNoteModal"></i>
                    </a>&nbsp;
                    <a href="#" id="'.$row['id'].'" title="Delete Note" class="text-danger deleteBtn">
                        <i class="fas fa-trash-alt fa-lg"></i>
                    </a>
                </td>
            </tr>';
        }
        $output .=' 
                </tbody></table>';
                echo $output;

    }
    else{
        echo '<h3 class="text-center text-secondary">:( You have not written any note yet! Write your first note now!</h3>';
    }
}

//Handle Edit Note of An User ajax request
if(isset($_POST['edit_id'])){
    $id=$_POST['edit_id'];

    $row=$curser->edit_note($id);
    echo json_encode($row);
}

//Handle Update Note of An User Ajax Request
if(isset($_POST['action']) && $_POST['action'] == 'update_note'){
      $id=$curser->test_input($_POST['id']);
      $title=$curser->test_input($_POST['title']);
    $note=$curser->test_input($_POST['note']);

     $curser->update_note($cid, $title, $note);
     $curser->notification($cid, "admin", 'Note updated');

}

//Handle Delete a Note of An User Ajax Request
if(isset($_POST['del_id'])){
    $id=$_POST['del_id'];

    $curser->delete_note($id);
    $curser->notification($cid, "admin", 'Note deleted');

}

//Handle Display a Note of An User Ajax Request
if(isset($_POST['info_id'])){
    $id=$_POST['info_id'];

    $row=$curser->edit_note($id);

    echo json_encode($row);
}

//Handle Profile Update AJax Request
if(isset($_FILES['image'])){
    $name=$curser->test_input($_POST['name']);
    $gender=$curser->test_input($_POST['gender']);
    $dob=$curser->test_input($_POST['dob']);
    $phone=$curser->test_input($_POST['phone']);

    $oldImage=$_POST['oldimage'];

    if(isset($_FILES['image']['name']) && ($_FILES['image']['name'] != "")){
        $newImage = $folder.$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'],  $newImage);

        if($oldImage != null){
            unlink($oldImage);
        }
    }else{
        $newImage = $oldImage;
    }
    $curser->update_profile($name,$gender,$dob,$phone,$newImage,$cid);
        $curser->notification($cid, "admin", 'Profile updated');

}

//Handle change Password Ajax Request
if(isset($_POST['action']) && $_POST['action'] == 'change_pass'){
   $currentPass = $_POST['curpass'];
   $newPass= $_POST['newpass'];
   $cnewPass= $_POST['cnewpass'];

   $hnewPass = password_hash($newPass, PASSWORD_DEFAULT);

   if($newPass != $cnewPass){
 echo $curser->showMessage('danger','Password did not matched!');
   }
else{
    if(password_verify($currentPass, $cpass)){
        $curser->change_password($hnewPass,$cid);
        echo $curser->showMessage('success', 'Password Changed Successfully!');
            $curser->notification($cid, "admin", 'Password changed');

    }else{
        echo $curser->showMessage('danger', 'Current Password is Wrong!');
    }
}
}

//Handle verify E-mail Ajax Request

if(isset($_POST['action']) && $_POST['action'] == 'verify_email'){
     try{
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth=true;
            $mail->Username = Database::USERNAME;
            $mail->Password = Database::PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom(Database::USERNAME,'User Management');
            $mail->addAddress($cemail);

            $mail->isHTML(true);
            $mail->Subject='E-Mail Verification';
            $mail->Body='<h3>Click the below link to verify your E-Mail.<br>
            <a href="http://localhost/user-system/verify-email.php?email='.$cemail.'">http://localhost/user-system/verify-email.php?email='.$cemail.'</a><br>Regards<br>User Management!</h3>';

            $mail->send();
            echo $curser->showMessage('success','Verification link sent to your E-Mail. Please check your mail!');

        }
        catch(Exception $e){
            echo $curser->showMessage('danger', 'Something went wrong please try agian later!');
        }       
}

//Handle Send Feedback to Admin Ajax Request
if(isset($_POST['action']) && $_POST['action'] == 'feedback'){
    $subject=$curser->test_input($_POST['subject']);
    $feedback=$curser->test_input($_POST['feedback']);

    $curser->send_feedback($subject,$feedback,$cid);
        $curser->notification($cid, "admin", 'Feedback written');

}

//Handle Fetch Notification
if(isset($_POST['action']) && $_POST['action'] == 'fetchNotification'){
    $notification = $curser->fetchNotification($cid);
    $output = '';

    if($notification){
        foreach($notification as $row){
            $output .= '<div class="alert alert-danger" role="alert">
                <button type="button" id="'.$row['id'].'" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>

                </button>
                <h4 class="alert-heading">
                    New Notification
                </h4>
                <p class="mb-0 lead">
                    '.$row['message'].'
                </p>
                <hr class="my-2">
                <p class="mb-0 float-left">
                    Reply of feedback from Admin
                </p>
                <p class="mb-0 float-right">
                    '.$curser->timeInAgo($row['created_at']).'
                </p>
                <div class="clearfix"></div>

            </div>';
        }
        echo $output;
    }
    else{
        echo '<h3 class="text-center text-secondary mt-5">
        No any new notification</h3>';
    }
}

//Check Notification
if(isset($_POST['action']) && $_POST['action'] == 'checkNotification'){
    if($curser->fetchNotification($cid)){
        echo '<i class="fas fa-circle fa-sm text-danger"></i>';
    }
    else{
        echo '';
    }
}

//Remove Notification
if(isset($_POST['notification_id'])){
    $id=$_POST['notification_id'];
    $curser->removeNotification($id);
}
?>