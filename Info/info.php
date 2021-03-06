<?php
/**
    This prints out information about room/apartment or house
**/
    if(isset($_GET['id'])){
        $roominfo = getRoomInfo($_GET['id'],$CONNECTION);
        $ownerinfo = getUserInfo($roominfo['GharMuliId'],$CONNECTION);
        if(!$roominfo){
            printMessage('Database Doesnot exitst');
            die();
        }
    }else{
        printMessage('Nothing To show');
        die();
    }

    /**
    Save Comment to database if its sent
    **/



    if(isset($_POST['submit_comment'])){
        if(isset($_COOKIE['user'])){
            $userid = $_COOKIE['user'];
            $postid = $_GET['id'];
            $commenttext = $_POST['CommentText'];
            if(!empty($commenttext)){
                saveCommentInDatabase($userid,$postid,$commenttext,$CONNECTION);
            }
        }else{
            printMessage("You have to login first to comment!");
        }
    }

    if(isset($_POST['submit_delete'])){
        $postid = $_GET['id'];
        if($_COOKIE['user'] == $postid){
            if(deletePost($postid,$CONN))
                echo "Post Deleted";
        }
    }

    /**
    If user applied for this post, create data in application table and mark it as in process post
    **/
 ?>

 <center>
<table>
        <tr>
            <td>Type</td>
            <td><?php switch ($roominfo['Type']) {
                case 'A':
                    echo "Apartment";
                    break;
                case 'R':
                    echo "Room";
                    break;
                case 'H':
                    echo "House";
                    break;
                default:
                    echo "Not Specified";
                    break;
            } ?>
        </td>
        </tr>
        <tr>
            <td>Name</td><td><?php echo $roominfo['Name']; ?></td>
        </tr>
        <tr>
            <td>Address</td><td><?php echo $roominfo['Address']; ?></td>
        </tr>
        <tr>
            <td>District</td><td><?php
                if($roominfo['District'] < 0 || $roominfo['District'] >= 75)
                    echo 'Unspecified';
                else
                    echo $district_name_list[$roominfo['District']];
            ?></td>
        </tr>
        <tr>
            <td>Ghar Number</td><td><?php echo $roominfo['GharNumber']; ?></td>
        </tr>
        <tr>
            <td>Rent</td><td><?php echo $roominfo['Rent'] ?></td>
        </tr>
        <tr>
            <td>RentNegotiable</td>
            <td><?php
                if($roominfo['RentNegotiable'] == 1){
                    echo 'YES';
                }else {
                    echo "NO";
                }
             ?></td>
        </tr>
        <tr>
            <td>PhoneNumber</td><td><?php echo $roominfo['PhoneNumber']; ?></td>
        </tr>
        <tr>
            <td>Water</td><td><?php
            if($roominfo['Water'] == 1){
                echo "YES";
            }else {
                echo "NO";
            }
             ?></td>
        </tr>
        <tr>
            <td>Water Bill</td><td><?php
            if(strcmp($roominfo['WaterBill'],'') == 0){
                echo "INCLUDED IN RENT";
            }else {
                echo $roominfo['WaterBill'];
            }
             ?></td>
        </tr>
        <tr>
            <td>Electricity</td><td><?php
            if($roominfo['Electricity'] == 1){
                echo "YES";
            }else {
                echo "NO";
            }
             ?></td>
        </tr>
        <tr>
            <td>Electricity Bill</td><td><?php
            if(strcmp($roominfo['ElectricityBill'],'') == 0){
                echo "INCLUDED IN RENT";
            }else {
                echo "Rs.".$roominfo['ElectricityBill']." /month";
            }
             ?></td>
        </tr>
        <tr>
            <td>Internet</td><td><?php
            if($roominfo['Internet'] == 1){
                echo "YES";
            }else {
                echo "NO";
            }
             ?></td>
        </tr>
        <tr>
            <td>Internet Bill</td><td><?php
            if(strcmp($roominfo['InternetBill'],'') == 0){
                echo "INCLUDED IN RENT";
            }else {
                echo "Rs.".$roominfo['InternetBill']." /month";
            }
             ?></td>
        </tr>
        <tr>
            <td>TransportationDistance</td><td><?php echo $roominfo['TransportationDistance'].' Km.'; ?></td>
        </tr>
        <tr>
            <td>Floor</td><td><?php echo $roominfo['Floor'] ?></td>
        </tr>
        <tr>
            <td>Discription</td><td><?php echo $roominfo['Discription'] ?></td>
        </tr>
        <tr>
            <td>Taken</td><td><?php
            if($roominfo['Taken'] == 1){
                echo "YES";
            }else {
                echo "NO";
            }
             ?></td>
        </tr>
        <tr>
            <td>Submitted Date</td>
            <td><?php
                if($roominfo['CreateDate'] != false){
                    echo $roominfo['CreateDate'];
                }else{
                    echo "Error !!";
                }
             ?></td>
        </tr>
        <?php
            if($roominfo['CreateDate'] != $roominfo['LastUpdate']){
                if($roominfo['LastUpdate'] != false){
                    echo "<tr><td>Last Updated</td><td>".$roominfo['LastUpdate']."</td></tr>";
                }
            }
         ?>
</table>
    <?php
    if(isset($_COOKIE['user'])){
        if($_COOKIE['user'] != $ownerinfo['UserID'] and $roominfo['Taken'] == 0){
        echo "<form name='apply_form' method='post'>
                <input type='submit' name='apply_for_post' value='Apply' />
                </form>";
        }
        else {
        echo "<form method='post'>
                <input type='submit' name='submit_delete' value='Delete' />
                </form>";
        }
    }
        ?>
</center>

    <h4 style="text-align:center"><u>Owner information</u></h4>
    <center>
<table>
        <tr><td>Full Name</td><td><a href="<?php echo "http://".$_SERVER['SERVER_NAME'].'/KothaBajar/Profile/?id='.$roominfo['GharMuliId']; ?>"><?php echo $ownerinfo['FullName'] ?></a></td></tr>
        <tr><td>UserName</td><td><?php echo $ownerinfo['Username'] ?></td></tr>
        <tr><td>Phone Number</td><td><?php echo $ownerinfo['PhoneNumber'] ?></td></tr>
        <tr><td>Email Address</td><td><?php echo $ownerinfo['EmailAddress'] ?></td></tr>
        <tr><td>Address</td><td><?php echo $ownerinfo['Address'] ?></td></tr>
        <tr><td>User Since</td><td><?php
        if($ownerinfo['CreateDate'] != false){
            echo $ownerinfo['CreateDate'];
        }else {
            echo "Error !!";
        }
         ?></td></tr>
</table>
</center>
    <h4 style="text-align:center"><u>Comments</u></h4>
    <center>
        <table>
            <?php
                $all_comments = getComments($_GET['id'], $CONNECTION);
                if($all_comments){
                    foreach ($all_comments as $value) {
                        $userinfo_c = getUserInfo($value['UserID'],$CONNECTION);
                        echo "<tr>";
                        echo "<td><a href='http://".$_SERVER['SERVER_NAME']."/KothaBajar/Profile/?id=".$userinfo_c['UserID']."'>".$userinfo_c['Username']."</a></td>";
                        echo "<td>".$value['CommentDate']."</td>";
                        echo "<td>".$value['CommentText']."</td>";
                        echo "</tr>";
                    }
                }else{
                    echo "<center>No Comments</center>";
                }
            ?>
        </table>
        <h4 style="text-align:center"><u>New Comment</u></h4>
        <form method="post">
            <textarea cols="40" row="30" name="CommentText"> </textarea>
            <input type="submit" value="Comment" name="submit_comment" />
        </form>
    </center>
