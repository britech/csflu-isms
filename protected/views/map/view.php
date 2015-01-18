<?php $this->renderPartial('commons/_notification', array('notif' => $notif)); ?>
<div class="column-group quarter-gutters">
    <div class="all-100">
        <div class="ink-alert block info align-center" style="margin-bottom: 0px; margin-top:0px;">
            <h4 style="padding: 0px; letter-spacing: 10px;"><?php echo strtoupper($strategyMap->name); ?></h4>
            <p style="margin: 0px;"><?php echo $strategyMap->visionStatement; ?></p>
        </div>
    </div>

    <div class="all-30">
        <div class="ink-alert block info" style="margin-top: 0px;">
            <h4 style="padding: 0px; text-align:center;">MISSION</h4>
            <?php $mission = explode("+", $strategyMap->missionStatement); ?>
            <p style="margin: 0px 0px 0px 10px;">
                <?php
                if (count($mission) < 1) {
                    echo "&lt;Not yet defined&gt;";
                } else {
                    foreach ($mission as $text) {
                        echo "-&nbsp;{$text}<br/>";
                    }
                }
                ?>
            </p>
        </div>
        <div class="ink-alert block info">
            <h4 style="padding: 0px; text-align:center;">VALUES</h4>
            <?php $values = explode("+", $strategyMap->valuesStatement); ?>
            <p style="margin: 0px 0px 0px 10px;">
                <?php
                if (count($values) < 1) {
                    echo "&lt;Not yet defined&gt;";
                } else {
                    foreach ($values as $text) {
                        echo "-&nbsp;{$text}<br/>";
                    }
                }
                ?>
            </p>
        </div>
    </div>

    <div class="all-70">
        <table class="ink-table bordered" style="margin-top: 0px;">
            <tbody>
                <?php foreach ($perspectives as $perspective): ?>
                    <tr>
                        <th style="width: 20%; background-color: black; color:white;" id="pers-<?php echo $perspective->id; ?>"><?php echo $perspective->description; ?></th>
                        <td>
                            <?php
                            $objectivesWithThemeCounter = 0;
                            $capturedThemes = array();
                            if (is_null($strategyMap->objectives) || count($strategyMap->objectives) < 1) {
                                echo "&lt;Not yet defined&gt;";
                            } else {
                                $themeDescriptions = array();
                                foreach ($themes as $theme) {
                                    array_push($themeDescriptions, $theme->description);
                                }

                                /**
                                 * Enlist objectives with no themes referenced with
                                 */
                                foreach ($strategyMap->objectives as $objective) {
                                    if ($perspective->id == $objective->perspective->id && is_null($objective->theme->id)) {
                                        echo "<span style=\"display: block;\">*&nbsp;{$objective->description}</span>";
                                    } elseif ($perspective->id == $objective->perspective->id && !is_null($objective->theme->id)) {
                                        $objectivesWithThemeCounter++;
                                        array_push($capturedThemes, $objective->theme->description);
                                    }
                                }

                                /**
                                 * Enlist objectives with themes defined
                                 */
                                if ($objectivesWithThemeCounter > 0) {
                                    $themesToBeDisplayed = array_intersect($themeDescriptions, $capturedThemes);
                                    foreach ($themesToBeDisplayed as $theme) {
                                        echo "<div class=\"ink-alert block info\">";

                                        foreach ($themes as $themeObject) {
                                            if ($theme == $themeObject->description) {
                                                $id = $themeObject->id;
                                                break;
                                            }
                                        }

                                        echo "<h4 style=\"padding: 0px; text-align:center;\">{$theme}</h4>";
                                        echo "<p style=\"margin: 0px 0px 0px 10px;\">";
                                        foreach ($strategyMap->objectives as $objective) {
                                            if ($perspective->id == $objective->perspective->id && $objective->theme->description == $theme) {
                                                echo "<span style=\"display: block;\">*&nbsp;{$objective->description}</span>";
                                            }
                                        }
                                        echo "</p>";
                                        echo "</div>";
                                    }
                                }
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>