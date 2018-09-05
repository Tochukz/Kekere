<!--@Layout('Layouts.master')-->
<div class="col-sm-12">
    <h4>Kekere is a light weight minimal PHP framework...</h4>
    <table class='table table-bordered"'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
            </tr>
        </thead>
        <tbody>
    <?php
      $bookStr = " ";
      foreach($books as $book){
         $bookStr .= '<tr>
                                       <td>'.$book->id.'</td>
                                      <td>'.$book->title.'</td>
                                     <td>'.$book->price.'</td>
                                </tr>';
        }
      echo $bookStr;     
      ?>   
          </tbody>
      </table>
</div>      