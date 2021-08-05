<?php
class bunker
{
    private $plnumber;
    private $lobbyname;
    public function createlobby($dblobbies, $nickname, $plnumber, $password)
    {
        $alllobby = $dblobbies->query("SELECT * FROM `info`");
        function checklobby($all)
        {
            while ($row = $all->fetch_assoc())
                if ($row['free'] == 0)
                    return $row['lbnumber'];
            return 0;
        }
        $lbnum = checklobby($alllobby);
        if ($lbnum == 0)
            return 0;
        $dblobbies->query("UPDATE `info` SET `plnumber` = '$plnumber', `lbpass` = MD5('$password'), 
                `free` = '1' WHERE `info`.`lbnumber` = $lbnum");
        $this->plnumber = $plnumber;
        $lbname = 'lobby' . $lbnum;
        $this->lobbyname = $lbname;
        $dblobbies->query("CREATE TABLE $this->lobbyname (
                    id INT(11) NOT NULL,
                    nickname VARCHAR(20) NOT NULL,
                    prof VARCHAR(50),
                    bio VARCHAR(50),
                    health VARCHAR(50),
                    charact VARCHAR(50),
                    hobby VARCHAR(50),
                    phobia VARCHAR(50),
                    addinfo VARCHAR(50),
                    baggage VARCHAR(50),
                    card1 VARCHAR(100),
                    card2 VARCHAR(100),
                    PRIMARY KEY(id),
                    UNIQUE KEY(nickname)
                )");
        $dblobbies->query("INSERT INTO $this->lobbyname (`id`, `nickname`) VALUES ('1', '$nickname')");
        return $this->lobbyname;
    }
    public function connectToLobby($dblobbies, $nickname, $lbnum, $password)
    {
        $lobby = $dblobbies->query("SELECT * FROM `info`");
        function findlobby($lobby, $lbnum)
        {
            while ($row = $lobby->fetch_assoc()) {
                if ($row['lbnumber'] == $lbnum)
                    return $row;
            }
            return 0;
        }
        $lobby = findlobby($lobby, $lbnum);
        if ($lobby == 0)
            return 0;
        if ($lobby['free'] != 1)
            return 0;
        if ($lobby['lbpass'] != md5($password))
            return "Wrong pass";
        $lbname = 'lobby' . $lbnum;
        function getId($dblobbies, $lbname, $plnum)
        {
            $temp = $dblobbies->query("SELECT * FROM `$lbname`");
            $IDs = array();
            $inc = 0;
            while ($row = $temp->fetch_assoc()) {
                $IDs[$inc] = $row['id'];
                $inc++;
            }
            $inc = 1;
            while ($inc <= $plnum) {
                $ans = 1;
                foreach ($IDs as $ID)
                    if (($inc == $ID) && $ans) {
                        $inc++;
                        $ans = 0;
                    }
                if ($ans)
                    break;
            }
            if ($inc > $plnum)
                return 0;
            return $inc;
        }
        $id = getId($dblobbies, $lbname, $lobby['plnumber']);
        if ($id == 0)
            return 0;
        $dblobbies->query("INSERT INTO `$lbname` (`id`, `nickname`) VALUES ('$id', '$nickname')");
        return $lobby;
    }
    public function leaveLobby($dblobbies, $nickname, $lbnum)
    {
        $lbname = 'lobby' . $lbnum;
        $temp = $dblobbies->query("SELECT * FROM `$lbname`");
        while ($row = $temp->fetch_assoc())
            if ($nickname == $row['nickname'])
                $dblobbies->query("DELETE FROM `$lbname` WHERE `$lbname`.`nickname` = '$nickname'");
    }
    public function start($dblobbies, $lbname)
    {
        $this->genAll($dblobbies, $lbname);
    }
    public function genAll($dblobbies, $lbname)
    {
        $user = 'admin';
        $password = '7f66JZuDKykJ16';
        $db = 'bunkerlist';
        $host = 'localhost';
        $dblist = new mysqli($host, $user, $password, $db);
        $dbgood = $dblist->query("SELECT * FROM `good`");
        $dbgood = $dbgood->fetch_all();
        $dbmedium = $dblist->query("SELECT * FROM `medium`");
        $dbmedium = $dbmedium->fetch_all();
        $dbbad = $dblist->query("SELECT * FROM `bad`");
        $dbbad = $dbbad->fetch_all();
        $dbcards = $dblist->query("SELECT * FROM `cards`");
        $dbcards = $dbcards->fetch_all();
        $dblist->close();
        $temp = $dblobbies->query("SELECT * FROM $lbname");
        while ($row = $temp->fetch_assoc()) {
            $player = $row['id'];
            $charnum = 8;
            $chararray = array("prof", "bio", "health", "charact", "hobby", "phobia", "addinfo", "baggage");
            $bad = random_int(0, 4);
            while ($bad) {
                $updchar = random_int(1, $charnum) - 1;
                $dblobbies->query("UPDATE `$lbname` SET `$chararray[$updchar]` = '3' WHERE `$lbname`.`id` = $player");
                unset($chararray[$updchar]);
                $chararray = array_values($chararray);
                $bad--;
                $charnum--;
            }
            $medium = random_int(2, 4);
            while ($medium) {
                $updchar = random_int(1, $charnum) - 1;
                $dblobbies->query("UPDATE `$lbname` SET `$chararray[$updchar]` = '2' WHERE `$lbname`.`id` = $player");
                unset($chararray[$updchar]);
                $chararray = array_values($chararray);
                $medium--;
                $charnum--;
            }
            foreach ($chararray as $name)
                $dblobbies->query("UPDATE `$lbname` SET `$name` = '1' WHERE `$lbname`.`id` = $player");
        }
        $temp = $dblobbies->query("SELECT * FROM $lbname");
        while ($row = $temp->fetch_assoc()) {
            unset($row['nickname']);
            $row = array_values($row);
            unset($row[9]);
            $chararray = array("prof", "bio", "health", "charact", "hobby", "phobia", "addinfo", "baggage");
            $inc = 0;
            while ($inc < 8) {
                switch ($row[$inc + 1]) {
                    case "3":
                        $newchar = $dbbad[random_int(1, count($dbbad)) - 1][$inc + 1];
                        $dblobbies->query("UPDATE `$lbname` SET `$chararray[$inc]` = '$newchar' WHERE `$lbname`.`id` = '$row[0]'");
                        break;
                    case "2":
                        $newchar = $dbmedium[random_int(1, count($dbmedium)) - 1][$inc + 1];
                        $dblobbies->query("UPDATE `$lbname` SET `$chararray[$inc]` = '$newchar' WHERE `$lbname`.`id` = '$row[0]'");
                        break;
                    case "1":
                        $newchar = $dbgood[random_int(1, count($dbgood)) - 1][$inc + 1];
                        $dblobbies->query("UPDATE `$lbname` SET `$chararray[$inc]` = '$newchar' WHERE `$lbname`.`id` = '$row[0]'");
                        break;
                }
                $inc++;
            }
            $newchar = $dbcards[random_int(1, count($dbcards)) - 1][1];
            $dblobbies->query("UPDATE `$lbname` SET `card1` = '$newchar' WHERE `$lbname`.`id` = '$row[0]'");
            $newchar = $dbcards[random_int(1, count($dbcards)) - 1][2];
            $dblobbies->query("UPDATE `$lbname` SET `card2` = '$newchar' WHERE `$lbname`.`id` = '$row[0]'");
        }
    }
    public function end($dblobbies, $lbname)
    {
        $this->lobbyname = $lbname;
        $lbnum = (int) str_replace("lobby", "", $lbname);
        $dblobbies->query("UPDATE `info` SET `plnumber` = '0', `lbpass` = '',
                `free` = '0' WHERE `info`.`lbnumber` = $lbnum");
        $plnum = $dblobbies->query("SELECT * FROM `info` WHERE `lbnumber` = $lbnum");
        $plnum = $plnum->fetch_assoc();
        $this->plnumber = $plnum['plnumber'];
        $dblobbies->query("DROP TABLE $this->lobbyname");
    }
}
