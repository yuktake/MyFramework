<html>
    <body>
        <table id="datatable" class="table table-striped" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>E-mail</th>
                    <th>DepartmentID</th>
                    <th>Rank</th>
                </tr>
            </thead>
            <tbody>
            <?php
                foreach($rows as $row) {
                    echo '<tr>' ;
                    echo "<td>" . $row->getId() . "</td>" ;
                    echo "<td>" . htmlspecialchars($row->getName(), ENT_QUOTES) . "</td>" ;
                    echo "<td>" . htmlspecialchars($row->getEmail(), ENT_QUOTES) . "</td>" ;
                    echo "<td>" . $row->getDepartmentId() . "</td>" ;
                    echo "<td>" . $row->getRank() . "</td>" ;
                    echo "</tr>" ;
                }
            ?>
            </tbody>
        </table> 
    </body>
</html>