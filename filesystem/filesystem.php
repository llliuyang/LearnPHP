<?php
 $path = 'filesys.txt';

// 1.copy(file,to_file) 复制文件到新路径,若文件存在，则覆盖
    copy($path,'./filesysdir/copyfile.txt');
   $copypath = './filesysdir/copyfile.txt';

// 2.basename(路径,扩展名),返回路径中的文件名部分，若规定了扩展名，当文件有匹配的扩展名，则不显示扩展名
  echo '02--文件名：'.basename($copypath).'<br>';  //输出：filesystem.txt
  echo '02--文件名不带扩展名：'.basename($copypath,'.txt').'<br>'; // 输出：filesystem

// 3.dirname(path) 返回文件路径的文件夹部分
  echo '03--文件路径文件夹部分：'. dirname($copypath).'<br>';

// 4.fileowner(filename) 返回指定文件的用户ID(所有者)，没有就返回false
  echo '04--文件所有者ID：'.fileowner($copypath).'<br>';

// 5.mkdir(path,mode,recursive,context)创建目录，成功返回true，否则false
// 只创建一次，先检查，如果有，则忽略，没有，则创建
   if(!file_exists('./newDir')){
       mkdir('./newDir');
   }else {
       echo '05-- newDir 目录已存在！<br>';
   }

// 6.move_uploaded_file(file,newloc)移动文件到新目录，若存在，则覆盖
// 该函数仅用于通过 HTTP POST 上传的文件。
 if(is_uploaded_file($path)){
   if (!file_exists('./newDir/filesys.txt')){
       move_uploaded_file($path,'./newDir/filesys.txt');
       echo "06--文件转移成功！<br>";
   }else{
       echo "06--文件已转移,并且存在<br>";
   }
 }else{
     echo '06--文件不是通过HTTP POST 方法上传的，无法使用此方法转移！<br>';
 }


// 7.获取文件信息
echo '07--文件权限：'. fileperms($copypath).'<br>';
echo '07--文件类型：'. filetype($copypath).'<br>';
echo '07--文件大小：'. filesize($copypath) / 1024 . 'kb<br>';
echo '07--绝对路径：'. realpath($copypath). '<br>';
print_r(pathinfo($copypath)) ;

// 8.fileatime(filename)返回文件上次访问的时间戳。该函数的结果会被缓存
echo "<br>08--文件上次访问时间: ".date("Y-m-d H:i:s.",fileatime($copypath)).'<br>';

// 9.filectime(filename)返回文件上次修改的时间戳。该函数的结果会被缓存
echo "09--文件上次修改时间: ".date("Y-m-d H:i:s.",filectime($copypath)).'<br>';

// 10.filemtime(filename)返回文件内容上次修改的时间戳。该函数的结果会被缓存
echo "10--文件内容上次修改时间: ".date("Y-m-d H:i:s.",filemtime($copypath)).'<br>';

// 11.disk_total_space(directory)返回指定目录的磁盘总容量，单位：字节
// 别名：diskfreespace(directory)
echo '11--C盘总容量：'.disk_total_space("C:") / (1024*1024*1024).'G<br>';

// 12.disk_free_space(directory)返回指定目录的磁盘可用容量，单位：字节
echo '12--C盘可用容量：'.disk_free_space("C:") / (1024*1024*1024).'G<br>';

// 13.fwrite(file,string,length) 函数将内容写入一个打开的文件中。
// 别名：fputs(file,string,length) 用法相同
// 若执行成功，返回写入的字节数，失败返回false。
// **只执行一次
$file = fopen($copypath,'a+') or exit("打开文件失败！<br>");
echo '13--写入的字节数为：'.fwrite($file,"\nthis is 4th line writein with fwrite").'<br>';
fclose($file);

// 14.file() 函数把整个文件读入一个数组中。
// 数组中的每个元素都是文件中相应的一行，包括换行符在内。
print_r(file($path));

// 15.fread ( resource $handle , int $length ) 函数读取打开的文件。
// 该函数返回读取的字符串，如果失败则返回 FALSE。
// 相当于截取操作并返回截取的字符串，再次操作就会在上次剩余的里面截取
$file = fopen($path,'a+') or exit("打开文件失败！<br>");
echo "<br>15--读取文件全部内容：".fread($file,filesize($path)).'<br>';
//echo "<br>15--读取文件20个字节：".fread($file,20).'<br>';
fclose($file);

// 16.ftruncate(file,size)把打开文件裁剪到指定的长度然后保存。
// 如果成功则返回 TRUE，如果失败则返回 FALSE。每次都执行。
/*echo '文件原始大小：'.filesize($path).'<br>';
$file16 = fopen($path,'a+');
ftruncate($file16,10);
fclose($file16);
clearstatcache();
echo '16--截取后的大小：'.filesize($path).'<br>';*/

// 17.rmdir(dir,context)删除空的目录。如果成功，该函数返回 TRUE。如果失败，则返回 FALSE。
// 前面加 @ 屏蔽错误信息
$emptyDir = 'filesysdir';
if(@rmdir($emptyDir)){
    echo "17--空文件夹".$emptyDir."已删除<br>";
}else {
    echo '17--'.$emptyDir.'不是空文件夹，如需删除，请手动删除！<br>';
}

// 18.文件指针的操作
$file18 = fopen($path,'r');
echo '18--初始指针位置：'.ftell($file18).'<br>';// ftell()返回当前指针位置
fseek($file18,"15"); // fseek(file,offset) 移动指针
echo '18--移动后指针位置：'.ftell($file18).'<br>';
rewind($file18); // rewind(file) 倒回指针到文件开头
echo '18--倒回后指针位置：'.ftell($file18).'<br>';
fclose($file18);

// 19.rename(oldname,newname,context)重命名文件或目录。
// 如果成功，该函数返回 TRUE。如果失败，则返回 FALSE。
$oldName = 'newDir';
$newName = 'renameDir';
if(file_exists($oldName)){
    $renameRes = @rename($oldName,$newName);
    if($renameRes){
        echo  "19--重命名成功！<br>";
    }else{
        if(file_exists($newName)){
           echo '19--'.$newName."已存在，无法重复命名<br>";
        }else {
            echo  "19--重命名失败，请检查后再试<br>";
        }
    }
}else {
    echo '19--'.$oldName.'不存在<br>';
}

// 20.file_get_contents() 把整个文件读入一个字符串中。
// 该函数是用于把文件的内容读入到一个字符串中的首选方法。
echo '20--'.file_get_contents($path).'<br>';

// 21.file_put_contents() 函数把一个字符串写入文件中。多次执行
// 默认是请空文件内容，然后添加。如果是向追加，添加 FILE_APPEND 标记
// 如果文件不存在，将创建一个文件
// 如果成功，该函数将返回写入文件中的字符数。如果失败，则返回 False。
// echo file_put_contents($path,'add a new line'); // 请空原文件内容，添加，返回添加字符数
// file_put_contents($path,"\nadd a new line", FILE_APPEND);// 文末追加

// 21.unlink() 函数删除文件。
//如果成功，该函数返回 TRUE。如果失败，则返回 FALSE。
$createfile21 = fopen('file21.txt','w+');
$file21 = 'file21.txt';
fclose($createfile21);

$unlink = unlink($file21);
if($unlink){
    echo "21--文件删除成功<br>";
}else {
    echo "21--文件删除失败！<br>";
}

// 22.clearstatcache() 函数清除文件状态缓存。
// PHP 会缓存某些函数的返回信息，以便提供更高的性能。
// 但是有时候，比如在一个脚本中多次检查同一个文件，
//而该文件在此脚本执行期间有被删除或修改的危险时，
//你需要清除文件状态缓存，以便获得正确的结果。
//要做到这一点，请使用 clearstatcache() 函数。
clearstatcache();