<?php 
class db extends dbconn {

    public function __construct()
    {
        $this->initDBO();
    }
    
    // -- START -- SELECT
    public function cekMenusUser($username,$id_menus)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT
                      tbl_users_menus.*,
                      tbl_menus.nama_menus,
                      tbl_menus.keterangan,
                      tbl_menus.status 
                    FROM
                      tbl_users_menus
                      INNER JOIN tbl_menus ON tbl_users_menus.id_menus = tbl_menus.id_menus
                    WHERE
                      tbl_users_menus.username = :username AND tbl_users_menus.id_menus = :id_menus AND tbl_menus.status = 'a' 
                    ";
            $stmt = $db->prepare($query);
            $stmt->bindParam("username",$username);
            $stmt->bindParam("id_menus",$id_menus);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }

    public function getReferensi($kode,$item)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT * FROM tbl_referensi WHERE kode = :kode AND item = :item";
            $stmt = $db->prepare($query);
            $stmt->bindParam("kode",$kode);
            $stmt->bindParam("item",$item);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }

    public function getReferensiByKode($kode)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT * FROM tbl_referensi WHERE kode = :kode AND status = 'a'";
            $stmt = $db->prepare($query);
            $stmt->bindParam("kode",$kode);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }

    public function getTable($hak_akses)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT
                        tbl_hak_akses.*,
                        tbl_menus.nama_menus,
                        tbl_menu.nama_menu,
                        tbl_menu_sub.nama_menu_sub,
                        tbl_menus.status
                    FROM
                        tbl_hak_akses
                        INNER JOIN tbl_menus ON tbl_hak_akses.id_menus = tbl_menus.id_menus
                        LEFT JOIN tbl_menu ON tbl_menus.id_menu = tbl_menu.id_menu
                        LEFT JOIN tbl_menu_sub ON tbl_menus.id_menu_sub = tbl_menu_sub.id_menu_sub 
                    WHERE
                        tbl_hak_akses.hak_akses = :hak_akses 
                    ORDER BY
                        LENGTH( tbl_menu.urut_menu ) ASC,
                        tbl_menu.urut_menu ASC,
                        LENGTH( tbl_menu_sub.urut_menu_sub ) ASC,
                        tbl_menu_sub.urut_menu_sub ASC,
                        tbl_menus.nama_menus ASC";
            $stmt = $db->prepare($query);
            $stmt->bindParam("hak_akses",$hak_akses);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }

    public function getHakAkses($kriteria)
    {
        $db = $this->dblocal;
        try
        {
            $query = "SELECT * FROM tbl_referensi WHERE kode = 'hak_akses' AND keterangan LIKE '%$kriteria%' AND status = 'a'";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            return $stat;
        }
    }

    public function getMenuByHakAkses($hak_akses,$idMenus)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT
                        tbl_hak_akses.*,
                        tbl_menu.nama_menu,
                        tbl_menu_sub.nama_menu_sub,
                        tbl_menus.nama_menus 
                    FROM
                        tbl_hak_akses
                        INNER JOIN tbl_menus ON tbl_hak_akses.id_menus = tbl_menus.id_menus
                        LEFT JOIN tbl_menu ON tbl_menus.id_menu = tbl_menu.id_menu
                        LEFT JOIN tbl_menu_sub ON tbl_menus.id_menu_sub = tbl_menu_sub.id_menu_sub 
                    WHERE
                        tbl_hak_akses.hak_akses = :hak_akses AND tbl_hak_akses.id_menus = :id_menus 
                    ORDER BY
                        LENGTH( tbl_menu.urut_menu ) ASC,
                        tbl_menu.urut_menu ASC,
                        LENGTH( tbl_menu_sub.urut_menu_sub ) ASC,
                        tbl_menu_sub.urut_menu_sub ASC,
                        LENGTH( tbl_menus.urut_menus ) ASC,
                        tbl_menus.urut_menus ASC";
            $stmt = $db->prepare($query);
            $stmt->bindParam("hak_akses",$hak_akses);
            $stmt->bindParam("id_menus",$idMenus);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }

    public function getMenus($hak_akses)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT
                        tbl_menus.*, 
                        tbl_menu.nama_menu, 
                        tbl_menu_sub.nama_menu_sub
                    FROM
                        tbl_menus
                        LEFT JOIN
                        tbl_menu
                        ON 
                            tbl_menus.id_menu = tbl_menu.id_menu
                        LEFT JOIN
                        tbl_menu_sub
                        ON 
                            tbl_menus.id_menu_sub = tbl_menu_sub.id_menu_sub
                    WHERE
                        id_menus NOT IN ((
                            SELECT
                                id_menus 
                            FROM
                                tbl_hak_akses 
                            WHERE
                                hak_akses = :hak_akses
                            ))
                    ORDER BY
                        tbl_menu.urut_menu ASC, 
                        tbl_menu_sub.urut_menu_sub ASC, 
                        tbl_menus.nama_menus ASC";
            $stmt = $db->prepare($query);
            $stmt->bindParam("hak_akses",$hak_akses);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }

    public function selectMenu($hak_akses)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT
                        tbl_users.username,
                        tbl_hak_akses.hak_akses,
                        tbl_hak_akses.id_menus,
                        tbl_hak_akses.c,
                        tbl_hak_akses.r,
                        tbl_hak_akses.u,
                        tbl_hak_akses.d 
                    FROM
                        tbl_users
                        INNER JOIN tbl_hak_akses ON tbl_users.hak_akses = tbl_hak_akses.hak_akses 
                    WHERE
                        tbl_users.hak_akses = :hak_akses 
                    ORDER BY
                        tbl_users.username ASC";
            $stmt = $db->prepare($query);
            $stmt->bindParam("hak_akses",$hak_akses);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }
    // -- END -- SELECT

    // -- START -- DELETE
    public function delete($id)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "DELETE FROM tbl_hak_akses WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam("id",$id);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = "HAPUS!";
            $stat[2] = "Menu berhasil dihapus.";
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = "HAPUS!";
            $stat[2] = $ex->getMessage();
            return $stat;
        }
    }

    public function deleteMenu($hak_akses)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "DELETE FROM tbl_users_menus WHERE username IN (
                    SELECT
                        tbl_users.username
                    FROM
                        tbl_users
                        INNER JOIN tbl_hak_akses ON tbl_users.hak_akses = tbl_hak_akses.hak_akses 
                    WHERE
                        tbl_users.hak_akses = :hak_akses 
                    ORDER BY
                        tbl_users.username ASC)";
            $stmt = $db->prepare($query);
            $stmt->bindParam("hak_akses",$hak_akses);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = "HAPUS!";
            $stat[2] = "Menu berhasil dihapus.";
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = "HAPUS!";
            $stat[2] = $ex->getMessage();
            return $stat;
        }
    }
    // -- END -- DELETE

    // -- START -- CREATE
    public function create($id, $hak_akses, $idMenus)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "INSERT INTO tbl_hak_akses (id, hak_akses, id_menus, c, r, u, d) VALUES (:id, :hak_akses, :id_menus, 'n', 'n', 'n', 'n')";
            $stmt = $db->prepare($query);
            $stmt->bindParam("id",$id);
            $stmt->bindParam("hak_akses",$hak_akses);
            $stmt->bindParam("id_menus",$idMenus);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = "TAMBAH!";
            $stat[2] = "Menu berhasil ditambahkan.";
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = "TAMBAH!";
            $stat[2] = $ex->getMessage();
            return $stat;
        }
    }

    public function createMenu($id,$username,$idMenus,$c,$r,$u,$d)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "INSERT INTO tbl_users_menus (id, username, id_menus, c, r, u, d) VALUES (:id, :username, :id_menus, :c, :r, :u, :d)";
            $stmt = $db->prepare($query);
            $stmt->bindParam("id",$id);
            $stmt->bindParam("username",$username);
            $stmt->bindParam("id_menus",$idMenus);
            $stmt->bindParam("c",$c);
            $stmt->bindParam("r",$r);
            $stmt->bindParam("u",$u);
            $stmt->bindParam("d",$d);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = "PERBAHARUI!";
            $stat[2] = "Perbaharui Menu berhasil.";
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = "PERBAHARUI!";
            $stat[2] = $ex->getMessage();
            return $stat;
        }
    }

    // -- END -- CREATE

    // -- START -- UPDATE
    public function edit_c_menu($id, $status)
    {
        $db = $this->dblocal;
        try
        {
            $query =    "UPDATE tbl_hak_akses SET c = :status WHERE id = :id ";
            $stmt = $db->prepare("$query");
            $stmt->bindParam("id",$id);
            $stmt->bindParam("status",$status);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = "Hak Akses berhasil dirubah.";
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            return $stat;
        }
    }

    public function edit_r_menu($id, $status)
    {
        $db = $this->dblocal;
        try
        {
            $query =    "UPDATE tbl_hak_akses SET r = :status WHERE id = :id ";
            $stmt = $db->prepare("$query");
            $stmt->bindParam("id",$id);
            $stmt->bindParam("status",$status);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = "Hak Akses berhasil dirubah.";
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            return $stat;
        }
    }

    public function edit_u_menu($id, $status)
    {
        $db = $this->dblocal;
        try
        {
            $query =    "UPDATE tbl_hak_akses SET u = :status WHERE id = :id ";
            $stmt = $db->prepare("$query");
            $stmt->bindParam("id",$id);
            $stmt->bindParam("status",$status);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = "Hak Akses berhasil dirubah.";
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            return $stat;
        }
    }

    public function edit_d_menu($id, $status)
    {
        $db = $this->dblocal;
        try
        {
            $query =    "UPDATE tbl_hak_akses SET d = :status WHERE id = :id ";
            $stmt = $db->prepare("$query");
            $stmt->bindParam("id",$id);
            $stmt->bindParam("status",$status);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = "Hak Akses berhasil dirubah.";
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            return $stat;
        }
    }
    // -- END -- UPDATE

}