<?php
$db = new Database;
$usr_id = $_SESSION['usr'] ?? 0;
$usr_profile = $db->getUser_ByID($usr_id);
if (!$usr_profile) {
    header('Refresh:0');
    die;
};

if (isset($_POST['saveEditImg'])) {
    $img_type = mime_content_type($_FILES['usr_img']['tmp_name']) ?? "";
    if (!($_FILES['usr_img'] ?? false)) {
        getAlert('ไม่พบรูปภาพ กรุณาอัพโหลดใหม่อีกครั้ง', 'danger');
    } elseif ($_FILES['usr_img']['size'] > 2048000) {
        getAlert('รูปภาพต้องมีขนาดไม่เกิน 2mb', 'danger');
    } elseif ($img_type != 'image/jpeg' && $img_type != 'image/png') {
        getAlert('รูปภาพต้องเป็น jpeg, png เท่านั้น', 'danger');
    } else {
        $img_name = md5($_FILES['usr_img']['name'] . rand()) . '.jpg';
        move_uploaded_file($_FILES['usr_img']['tmp_name'], './public/profile/' . $img_name);
        if (file_exists('./public/profile/' . $usr_profile['usr_img']))
            unlink('./public/profile/' . $usr_profile['usr_img']);
        $db->updateImgProfile($usr_profile['usr_id'], $img_name);
        header("Refresh:0");
        die;
    }
}
?>

<div class="w-full">
    <div class="text-right">
        <a class="px-3 py-2 bg-gray-500 rounded-lg inline-block mt-3 text-white" href="/<?php echo $usr_profile['usr_username']; ?>">ย้อนกลับ</a>
    </div>
    <h3 class="heading">แก้ไขโปรไฟล์</h3>
    <form class="form-control" enctype="multipart/form-data" method="post">
        <input type="hidden" name="editImg">
        <div>
            <div class="p-3 text-center"><img id="blah" class="w-24 h-24 object-cover rounded-full inline-block" src="/public/profile/<?php echo $usr_profile['usr_img']; ?>" alt=""></div>
        </div>

        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">อัฟโหลดรูปภาพ</label>
        <input id="dropzone-file" class="input-text block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold  mb-1 file:bg-blue-50 file:text-blue-700       hover:file:bg-violet-100" type="file" accept="image/jpeg,image/png" name="usr_img" required>
        <p class="mb-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">png, jpeg or jpg </p>

        <div>
            <button name="saveEditImg" class="bg-blue-600 text-white py-2 px-3 rounded-lg w-full">บันทึก</button>
        </div>
    </form>
</div>


<script>
    const input_img = document.getElementById("dropzone-file")
    input_img.onchange = evt => {
        const [file] = input_img.files
        if (file) {
            const img_tag = document.getElementById("blah");
            img_tag.src = URL.createObjectURL(file)
        }
    }
</script>