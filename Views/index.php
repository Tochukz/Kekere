<!--@Layout('Layouts.master')-->
<div class="col-sm-12">
    <h4>Kekere is a light weight minimal PHP framework...</h4>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
            </tr>
        </thead>
        <tbody><!--
    <?php/*
      $tableStr = " ";
      foreach($books as $book){
         $tableStr .= '<tr>
                                       <td>'.$table->id.'</td>
                                      <td>'.$table->title.'</td>
                                     <td>'.$table->author.'</td>
                                </tr>';
      }
      echo $tableStr;  */
     
    ?>-->
       <?php dump($books); ?>
          </tbody>
      </table>
</div>      