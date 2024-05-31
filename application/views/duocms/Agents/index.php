<h3>Agenci</h3>
<p>
    Tutaj znajduje się lista wszystkich agentów.
</p>
<table>
    <thead>
        <tr style="align-content: center;">
            <th>Id</th>
            <th>Miejscowość</th>
            <th>Nazwa</th>
            <th>Adres</th>
            <th>Tel. kontaktowy</th>
            <th>E-mail</th>
            <th colspan="2">Akcja</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if(!empty($agents)){
            foreach ($agents as $agent){
                echo '<tr>';
                echo '<td>' . $agent->id . '</td>';
                echo '<td>' . $agent->city . '</td>';
                echo '<td>' . $agent->name . '</td>';
                echo '<td>' . $agent->address . '</td>';
                echo '<td>' . $agent->tel . '</td>';
                echo '<td>' . $agent->email . '</td>';
                echo '<td>';
                    printf(ADMIN_BUTTON_EDIT, site_url('duocms/agents/edit/'.$agent->id)); 
                echo '</td><td>';
                    printf(ADMIN_BUTTON_DELETE, site_url('duocms/agents/delete/'.$agent->id));
                echo ' </td>';
                echo '</tr>';
            }
        }
        ?>
    </tbody>
    <tfoot>
    <form method="POST">
        <tr>
            <td></td>
            <td>
                <input type="text" name="city" value="" placeholder="Wpisz miejscowość" required="true"/>
            </td>
            <td>
                <input type="text" name="name" value="" placeholder="Wpisz nazwę" required="true"/>
            </td>
            <td>
                <input type="text" name="address" value="" placeholder="Wpisz adres" required="true"/>
            </td>
            <td>
                <input type="text" name="tel" value="" placeholder="Telefon kontaktowy" required="true"/>
            </td>
            <td>
                <input type="text" name="email" value="" placeholder="Adres E-mail" required="true"/>
            </td>
            <td colspan="2">
                <input type="submit" value="Dodaj agenta" />
            </td>
        </tr>
    </form>
    </tfoot>
</table>