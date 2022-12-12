<?php
class DataBase
{
    const DB_FILENAME = 'data/stats.db';
    const DB_TABLE = "stats";

    private function getConnection() {
        $db = new SQLite3(self::DB_FILENAME);

        if(!$db){
            echo "La connection a la base SQLite a échoué :(\n";
        }
        $db->exec("CREATE TABLE IF NOT EXISTS ".self::DB_TABLE." (date TEXT UNIQUE)");
        return $db;
    }


    public function getColumns($connection = null) {
        if ($connection==null) {
            $connection = $this->getConnection();
        }

        $columns = [];
        $tablesquery = $connection->query("PRAGMA table_info(stats);");
        while ($table = $tablesquery->fetchArray(SQLITE3_ASSOC)) {
            array_push($columns, $table['name']);
        }
        return $columns;
    }


    public function hasCol($colName, $connection = null) {
        $columns = $this->getColumns($connection);

        return in_array($colName, $columns);
    }

    public function addNewEvent($event) {
        $connection = $this->getConnection();

        // Test if event already exists
        if (!$this->hasCol($event, $connection)) {
            echo "Create new column named `".$event."`";
            $connection->exec("ALTER TABLE ".self::DB_TABLE." ADD `".$event."` INT default 0");
        }

        // Test if year/month exists
        $date = date("Y-m");
        $cmd = "INSERT OR IGNORE INTO ".self::DB_TABLE." (date) VALUES ('".$date."')";
        $connection->exec($cmd);

        // Add event
        $connection->exec("UPDATE ".self::DB_TABLE." SET `".$event."` = `".$event."`+1 WHERE date='".$date."'");
    }

    public function showTable() {
        $columns = $this->getColumns();
        $data = $this->getTable();
        echo '<table>'.PHP_EOL;
        echo '      <tr>';
        foreach ($columns as $col) {
            echo "<td>".$col."</td>";
        }
        echo '</tr>'.PHP_EOL;
        foreach ($data['date'] as $i => $date) {
            echo '      <tr>';
            foreach ($columns as $col) {
                echo "<td>".$data[$col][$i]."</td>";
            }
            echo '</tr>'.PHP_EOL;
        }
        echo '  </table>'.PHP_EOL;
    }

    public function getTable() {
        $connection = $this->getConnection();
        $data = ['date' => []];
        $columns = $this->getColumns($connection);
        foreach ($columns as $col) {
            $data += array($col => []);
        }

        $results = $connection->query("SELECT * FROM ".self::DB_TABLE);
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            foreach ($columns as $col) {
                array_push($data[$col], $row[$col]);
            }
        }
        return $data;
    }
}

$db = new DataBase;

// =====================================================================================================================
// Add new event
// =====================================================================================================================
if(isset($_GET['event'])) {
    $event = str_replace("-", "_", $_GET['event']);
    echo "Add event: '".$event."'".PHP_EOL;
    $db->addNewEvent($event);
}
// =====================================================================================================================
// Show the plot
// =====================================================================================================================
else { ?>

    <script type="text/javascript"  src="js/plotly-2.14.0.min.js"></script>
    <script type="text/javascript"  src="js/plotly-locale-fr.js"></script>
    <script>Plotly.setPlotConfig({locale: 'fr'})</script>
    <div id='plotlyCharte' style="height: 400px;"><!-- Plotly chart will be drawn inside this DIV --></div>
    <script>
        var data = JSON.parse('<?php echo json_encode($db->getTable()) ?>');

        var layout = {
            title: 'Monitoring des visites',
            showlegend: true
        };

        traces = [];
        for (let col in data) {
            if (col == "date") {
                continue;
            }

            let trace = {
                x: data['date'],
                y: data[col],
                name: col,
                "xaxis": {  // To sort x axis by time
                    "title": "Idea",
                    "categoryorder": "array",
                    "categoryarray":  data['date'].sort()
                }
            };
            traces.push(trace);

        }


        Plotly.newPlot('plotlyCharte', traces, layout, {responsive: true, scrollZoom: true});
    </script>
<?php
} ?>
