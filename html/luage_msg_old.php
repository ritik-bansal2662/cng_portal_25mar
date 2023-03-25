<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="w3.css">
    <title>Reconciliation at Mother Gas Station</title>
    <style>
        container {
            margin: 50px 50px;
        }

        thead {
            color: white;

        }

        tbody {
            color: black;

        }

        tfoot {
            color: red;
        }

        table,
        th,
        td {
            border: 1px solid white;
        }

        .ddtf-processed th.option-item>select {
            display: none;
        }

        .ddtf-processed th.option-item>div {
            display: block !important;
        }
    </style>
</head>

<body>
    <div class="w3-container ">
        <div class="w3-responsive">
            <h2 align="center">Reconciliation at Mother Gas Station</h2>
            <!-- <p>*BRC: Before Refilling of Cascade,*MFMR: Mass Flow Meter Readings,*AFC: After Refilling of Cascade
            </p> -->
            <table class="w3-table-all w3-small" style="width: 110%;">
                <thead>
                    <tr>
                        <th bgcolor=" #02603E" class="header option-item" scope="col" style="width: 39.5%;" colspan="4"><strong></strong></th>
                        <th bgcolor=" #02603E" class="header option-item" scope="col" style="width: 15%;" colspan="4"><strong>Before Refilling of Cascade</strong></th>
                        <th bgcolor=" #02603E" class="header option-item" scope="col" style="width: 22.5%;" colspan="3"><strong>Mass Flow Meter Readings</strong></th>
                        <th bgcolor=" #02603E" class="header option-item" scope="col" style="width: 18.5%;" colspan="4"><strong>After Refilling of Cascade</strong></th>
                        <th bgcolor=" #02603E" class="header option-item" style="width: 16%;"><strong>Gas filled at MGS</strong></th>
                    </tr>
                </thead>
            </table>
            <table id="mytable" class="w3-table-all w3-small">
                <thead>
                    <!-- <tr>
                        <th bgcolor=" #02603E" class="header option-item" scope="col" colspan="4"><strong></strong></th>
                        <th bgcolor=" #02603E" class="header option-item" scope="col" colspan="4"><strong>Before Refilling of Cascade</strong></th>
                        <th bgcolor=" #02603E" class="header option-item" scope="col" colspan="3"><strong>Mass Flow Meter Readings</strong></th>
                        <th bgcolor=" #02603E" class="header option-item" scope="col" colspan="4"><strong>After Refilling of Cascade</strong></th>
                        <th bgcolor=" #02603E" class="header option-item"><strong>Gas filled at MGS</strong></th>
                    </tr> -->
                    <tr>
                        <th bgcolor=" #02603E" class="header" scope="col"><strong>Date</strong></th>
                        <th bgcolor=" #02603E" class="header" scope="col"><strong>LCV Number </strong></th>
                        <th bgcolor=" #02603E" class="header" scope="col"><strong>Mother Gas Station </strong></th>
                        <th bgcolor=" #02603E" class="header" scope="col"><strong>Daughter Booster Station </strong></th>
                        <th bgcolor=" #02603E" class="header option-item" scope="col"><strong>Pressure (Bar) </strong></th>
                        <th bgcolor=" #02603E" class="header option-item" scope="col"><strong>Temperature (Degree C) </strong></th>
                        <th bgcolor=" #02603E" class="header option-item" scope="col"><strong>Volume (WL) </strong></th>
                        <th bgcolor=" #02603E" class="header option-item" scope="col"><strong>Mass of Gas (Kg) </strong></th>
                        <th bgcolor=" #02603E" class="header option-item" scope="col"><strong> Mass Flow rate of Gas (Kg/hr) </strong></th>
                        <th bgcolor=" #02603E" class="header option-item" scope="col"><strong> Time Taken to fill the Cascade (H:MM:SS) </strong></th>
                        <th bgcolor=" #02603E" class="header option-item" scope="col"><strong> Amount of Gas Filled(Through MFM) (Kg) </strong></th>
                        <th bgcolor=" #02603E" class="header option-item" scope="col"><strong> Pressure (Bar) </strong></th>
                        <th bgcolor=" #02603E" class="header option-item" scope="col"><strong> Temperature (Degree C) </strong></th>
                        <th bgcolor=" #02603E" class="header option-item" scope="col"><strong> Volume (WL) </strong></th>
                        <th bgcolor=" #02603E" class="header option-item" scope="col"><strong> Mass of Gas (Kg) </strong></th>
                        <th bgcolor=" #02603E" class="header option-item" scope="col"><strong> Gas filled at MGS(Kg) </strong></th>


                    </tr>

                </thead>
                <tbody style="background-color: #E9C006; ">
                    <?php
                    $db = new PDO("mysql:host=localhost;dbname=cng_luag", "root", "");
                    function decimalHours($time)
                    {
                        $hms = explode(":", $time);
                        return ($hms[0] + ($hms[1] / 60) + ($hms[2] / 3600));
                    }
                    $stmt = $db->prepare("SELECT date_reading,create_date,lcv_id,station_id,dbs_station_id,before_filing_at_mgs_value_pressure_gauge_read,
                     before_filing_at_mgs_value_temperature_gauge_read,before_filing_at_mgs_mass_cng,after_filling_at_mgs_mfm_value_read,time_taken_to_fill_lcv,
                     (TIME_TO_SEC(`time_taken_to_fill_lcv`)/3600)*after_filling_at_mgs_mfm_value_read total_mass_through_mfm,after_filling_at_mgs_value_pressure_gauge_read,
                     after_filling_at_mgs_value_temperature_gauge_read,after_filling_at_mgs_mass_cng,total_gas_mgs
                      from luag_transaction_master_dbs_station where before_filing_at_mgs_value_temperature_gauge_read	is not NULL
                      order by sl_no desc");
                    $stmt->execute();
                    while ($row = $stmt->fetch()) {
                    ?>
                        <tr>

                            <td bgcolor="#E9C006">
                                <?php echo $row["date_reading"]; ?>
                            </td>
                            <td bgcolor="#E9C006">
                                <?php echo $row["lcv_id"]; ?>
                            </td>
                            <td bgcolor="#E9C006">
                                <?php echo $row["station_id"]; ?>
                            </td>
                            <td bgcolor="#E9C006">
                                <?php echo $row["dbs_station_id"]; ?>
                            </td>
                            <td bgcolor="#E9C006">
                                <?php echo round($row["before_filing_at_mgs_value_pressure_gauge_read"], 2); ?>
                            </td>
                            <td bgcolor="#E9C006">
                                <?php echo round($row["before_filing_at_mgs_value_temperature_gauge_read"], 2); ?>
                            </td>
                            <td bgcolor="#E9C006">
                                3000
                            </td>
                            <td bgcolor="#E9C006">
                                <?php echo round($row["before_filing_at_mgs_mass_cng"], 2); ?>
                            </td>
                            <td bgcolor="#E9C006">
                                <?php echo $row["after_filling_at_mgs_mfm_value_read"]; ?>
                            </td>
                            <td bgcolor="#E9C006">
                                <?php echo $row["time_taken_to_fill_lcv"]; ?>
                            </td>

                            <td bgcolor="#E9C006"><?php

                                                    echo $row["total_mass_through_mfm"]; ?>
                            </td>
                            <td bgcolor="#E9C006">
                                <?php echo round($row["after_filling_at_mgs_value_pressure_gauge_read"], 2); ?>
                            </td>
                            <td bgcolor="#E9C006">
                                <?php echo round($row["after_filling_at_mgs_value_temperature_gauge_read"], 2); ?>
                            </td>
                            <td bgcolor="#E9C006">
                                3000
                            </td>
                            <td bgcolor="#E9C006">
                                <?php echo round($row["after_filling_at_mgs_mass_cng"], 2); ?>
                            </td>
                            <td bgcolor="#E9C006">
                                <?php echo round($row["total_gas_mgs"], 2); ?>
                            </td>




                        </tr>
                    <?php
                        $decimalHours = 0;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="ddtf.js"></script>
    <script>
        $("#mytable").ddTableFilter();
    </script>



</body>

</html>