<?php

    require_once 'config.php';

    class Auth extends Database{
        
        //Register New User
        public function register($name,$email,$password){
            $sql="INSERT INTO users (name,email,password) VALUES(:name,:email,:pass)";
            $stmt=$this->conn->prepare($sql);
            $stmt->execute(['name'=>$name,'email'=>$email,'pass'=>$password]);
            return true;
        }

        //Check if user already registered
        public function user_exist($email){
            $sql="SELECT email FROM users WHERE email=:email";
            $stmt=$this->conn->prepare($sql);
            $stmt->execute(['email'=>$email]);
            $result=$stmt->fetch(PDO::FETCH_ASSOC);

            return $result;
        }

        //Login Existing User
        public function login($email){
            $sql="SELECT email,password FROM users WHERE email=:email AND deleted !=0";
            $stmt=$this->conn->prepare($sql);
            $stmt->execute(['email'=>$email]);
            $row=$stmt->fetch(PDO::FETCH_ASSOC);

            return $row;
        }

        //Current User In Session
        public function currentUser($email){
              $sql="SELECT * FROM users WHERE email=:email AND deleted !=0";
            $stmt=$this->conn->prepare($sql);
            $stmt->execute(['email'=>$email]);
            $row=$stmt->fetch(PDO::FETCH_ASSOC);

            return $row;
        }

        //Forgot Password
        public function forgot_password($token,$email){
            $sql= "UPDATE users 
               SET token = :token, 
                    token_expire = DATE_ADD(NOW(), INTERVAL 10 MINUTE) 
                WHERE email = :email
                ";
            $stmt=$this->conn->prepare($sql);
            $stmt->execute(['token'=>$token,'email'=>$email]);
            return true;
        }
    

    //Add New Note
    public function add_new_note($uid,$title,$note){
        $sql="INSERT INTO notes (uid, title, note) VALUES (:uid, :title, :note)";
        $stmt=$this->conn->prepare($sql);
        $stmt->execute(['uid'=>$uid,'title'=>$title,'note'=>$note]);
        return true;
    }

    //Fetch All Notes of An User
    public function get_notes($uid){
        $sql="SELECT * FROM notes WHERE uid = :uid";
        $stmt=$this->conn->prepare($sql);
        $stmt->execute(['uid'=>$uid]);

        $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    //Edit Note of An User
    public function edit_note($id){
        $sql="SELECT * FROM notes WHERE id= :id";
        $stmt=$this->conn->prepare($sql);
        $stmt->execute(['id'=>$id]);

        $result=$stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    //Update Note of An User
    public function update_note($id, $title, $note){
        $sql="UPDATE notes SET title = :title, note = :note, updated_at = NOW() WHERE id = :id";
        $stmt=$this->conn->prepare($sql);
        $stmt->execute(['title'=>$title, 'note'=>$note, 'id'=>$id]);
        return true;
    }

    //Delete a Note of An User
    public function delete_note($id){
        $sql="DELETE FROM notes WHERE id = :id";
        $stmt=$this->conn->prepare($sql);
        $stmt->execute(['id'=>$id]);
        return true;
    }

    //Update Profile Of An User
    public function update_profile($name,$gender,$dob,$phone,$photo,$id){
        $sql="UPDATE users SET name = :name, gender=:gender, dob= :dob,
         phone = :phone, photo= :photo WHERE id =:id AND deleted !=0";
         $stmt=$this->conn->prepare($sql);
         $stmt->execute(['name'=>$name,'gender'=>$gender,'dob'=>$dob,'phone'=>$phone,
        'photo'=>$photo,'id'=>$id ]);
        return true;
    }

    //Change Password of An User
    public function change_password($pass,$id){
        $sql="UPDATE users SET password = :pass WHERE id=:id AND deleted !=0";
        $stmt=$this->conn->prepare($sql);
        $stmt->execute(['pass'=>$pass,'id'=>$id]);
        return true;
    }
    //Verified E-Mail of An User
    public function verify_email($email){
        $sql="UPDATE users SET verified = 1 WHERE email=:email AND deleted !=0";
        $stmt=$this->conn->prepare($sql);
        $stmt->execute(['email'=>$email]);
        return true;
    }

    //Send Feedback to Admin
    public function send_feedback($sub,$feed,$uid){
        $sql="INSERT INTO feedback (uid, subject,feedback) VALUES (:uid,:sub,:feed)";
        $stmt=$this->conn->prepare($sql);
        $stmt->execute(['uid'=>$uid,'sub'=>$sub,'feed'=>$feed]);
        return true;
    }

    //Insert Notification
    public function notification($uid,$type,$message){
        $sql="INSERT INTO notification (uid, type,message) VALUES (:uid,:type,:message)";
        $stmt=$this->conn->prepare($sql);
        $stmt->execute(['uid'=>$uid,'type'=>$type,'message'=>$message]);
        return true;
    }

    //Fetch Notification
     public function fetchNotification($uid){
        $sql="SELECT * FROM notification WHERE uid = :uid AND type= 'user'";
        $stmt=$this->conn->prepare($sql);
        $stmt->execute(['uid'=>$uid]);

        $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    //Remove notification
    public function removeNotification($id){
        $sql="DELETE FROM notification WHERE id =:id AND type='user'";
        $stmt=$this->conn->prepare($sql);
        $stmt->execute(['id'=>$id]);
        return true;
    }
}
?>