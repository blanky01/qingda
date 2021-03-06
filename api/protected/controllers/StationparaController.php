<?php

class StationparaController extends Controller
{
	public function actionIndex()
	{
        $this->setPageCount();
        $site = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{site}}')
            ->queryAll();
        $data = array();
        if ($site) {
            foreach ($site as $key => $value) {
                $data[$value['sid']] = $value;
            }
        }

        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
        $batteryparm = Yii::app()->bms->createCommand()
            ->select('*')
            ->from('{{station_parameter}}')
            ->limit($this->count)
            ->offset(($this->page-1)*$this->count)
            ->order('sid desc')
            ->queryAll();
        if ($batteryparm) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;

            foreach($batteryparm as $key=>$value){
                if (isset($data[$value['sid']])) {
                    $value['site_name'] = $data[$value['sid']]['site_name'];
                }else{
                    $value['site_name'] = '未添加站点';
                }
                $ret['data']['list'][] = $value;
            }
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无组参数数据！'
            );
        }

        echo json_encode($ret);
	}

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView()
    {
        $id = Yii::app()->request->getParam('sid',0);
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();

        if ($id) {
            $sql = "select * from {{station_parameter}}
                    where sid=" . $id;
            $row = Yii::app()->bms->createCommand($sql)->queryRow();
            if ($row) {
                $ret['data'] = $row;
            }else{
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'没有该站参数！'
                );
            }
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'没有该站参数！'
            );
        }
        echo json_encode($ret);

    }
    public function actionCreate()
    {

        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();
        $sid = Yii::app()->request->getParam('sid' ,0);
        $station_sn_key=Yii::app()->request->getParam('station_sn_key','');
        $MAC_address=Yii::app()->request->getParam('MAC_address','');
        $N_Groups_Incide=Yii::app()->request->getParam('N_Groups_Incide','');
        $Time_interval_Rin=Yii::app()->request->getParam('Time_interval_Rin','');
        $Time_interval_U=Yii::app()->request->getParam('Time_interval_U','');
        $U_abnormal_limit=Yii::app()->request->getParam('U_abnormal_limit','');
        $T_abnormal_limit=Yii::app()->request->getParam('T_abnormal_limit','');
        $Rin_abnormal_limit=Yii::app()->request->getParam('Rin_abnormal_limit','');
        $T_upper_limit=Yii::app()->request->getParam('T_upper_limit','');
        $T_lower_limit=Yii::app()->request->getParam('T_lower_limit','');
        $Humi_upper_limit=Yii::app()->request->getParam('Humi_upper_limit','');
        $Humi_lower_limit=Yii::app()->request->getParam('Humi_lower_limit','');
        $Group_I_criterion=Yii::app()->request->getParam('Group_I_criterion','');
        $bytegeStatus_U_upper=Yii::app()->request->getParam('bytegeStatus_U_upper','');
        $bytegeStatus_U_lower=Yii::app()->request->getParam('bytegeStatus_U_lower','');
        $FloatingbytegeStatus_U_upper=Yii::app()->request->getParam('FloatingbytegeStatus_U_upper','');
        $FloatingbytegeStatus_U_lower=Yii::app()->request->getParam('FloatingbytegeStatus_U_lower','');
        $DisbytegeStatus_U_upper=Yii::app()->request->getParam('DisbytegeStatus_U_upper','');
        $DisbytegeStatus_U_lower=Yii::app()->request->getParam('DisbytegeStatus_U_lower','');
        $N_Groups_Incide_Station=Yii::app()->request->getParam('N_Groups_Incide_Station','');
        $HaveCurrentSensor=Yii::app()->request->getParam('HaveCurrentSensor','');
        $StationCurrentSensorSpan=Yii::app()->request->getParam('StationCurrentSensorSpan','');
        $StationCurrentSensorZeroADCode=Yii::app()->request->getParam('StationCurrentSensorZeroADCode','');
        $OSC=Yii::app()->request->getParam('OSC','');
        $DisbytegeCurrentLimit=Yii::app()->request->getParam('DisbytegeCurrentLimit','');
        $bytegeCurrentLimit=Yii::app()->request->getParam('bytegeCurrentLimit','');
        $TemperatureHighLimit=Yii::app()->request->getParam('TemperatureHighLimit','');
        $TemperatureLowLimit=Yii::app()->request->getParam('TemperatureLowLimit','');
        $HumiH=Yii::app()->request->getParam('HumiH','');
        $HumiL=Yii::app()->request->getParam('HumiL','');
        $TemperatureAdjust=Yii::app()->request->getParam('TemperatureAdjust','');
        $HumiAdjust=Yii::app()->request->getParam('HumiAdjust','');
        if ($sid) {
            $row = array();
            $station_sn_key !='' && $row['station_sn_key']=$station_sn_key;
            $MAC_address !='' && $row['MAC_address']=$MAC_address;
            $sid !='' && $row['sid']= $sid;
            $N_Groups_Incide !='' && $row['N_Groups_Incide']=$N_Groups_Incide;
            $Time_interval_Rin !='' && $row['Time_interval_Rin']=$Time_interval_Rin;
            $Time_interval_U !='' && $row['Time_interval_U']=$Time_interval_U;
            $U_abnormal_limit !='' && $row['U_abnormal_limit']=$U_abnormal_limit;
            $T_abnormal_limit !='' && $row['T_abnormal_limit']=$T_abnormal_limit;
            $Rin_abnormal_limit !='' && $row['Rin_abnormal_limit']=$Rin_abnormal_limit;
            $T_upper_limit !='' && $row['T_upper_limit']=$T_upper_limit;
            $T_lower_limit !='' && $row['T_lower_limit']=$T_lower_limit;
            $Humi_upper_limit !='' && $row['Humi_upper_limit']=$Humi_upper_limit;
            $Humi_lower_limit !='' && $row['Humi_lower_limit']=$Humi_lower_limit;
            $Group_I_criterion !='' && $row['Group_I_criterion']=$Group_I_criterion;
            $bytegeStatus_U_upper !='' && $row['bytegeStatus_U_upper']=$bytegeStatus_U_upper;
            $bytegeStatus_U_lower !='' && $row['bytegeStatus_U_lower']=$bytegeStatus_U_lower;
            $FloatingbytegeStatus_U_upper !='' && $row['FloatingbytegeStatus_U_upper']=$FloatingbytegeStatus_U_upper;
            $FloatingbytegeStatus_U_lower !='' && $row['FloatingbytegeStatus_U_lower']=$FloatingbytegeStatus_U_lower;
            $DisbytegeStatus_U_upper !='' && $row['DisbytegeStatus_U_upper']=$DisbytegeStatus_U_upper;
            $DisbytegeStatus_U_lower !='' && $row['DisbytegeStatus_U_lower']=$DisbytegeStatus_U_lower;
            $N_Groups_Incide_Station !='' && $row['N_Groups_Incide_Station']=$N_Groups_Incide_Station;
            $HaveCurrentSensor !='' && $row['HaveCurrentSensor']=$HaveCurrentSensor;
            $StationCurrentSensorSpan !='' && $row['StationCurrentSensorSpan']=$StationCurrentSensorSpan;
            $StationCurrentSensorZeroADCode !='' && $row['StationCurrentSensorZeroADCode']=$StationCurrentSensorZeroADCode;
            $OSC !='' && $row['OSC']=$OSC;
            $DisbytegeCurrentLimit !='' && $row['DisbytegeCurrentLimit']=$DisbytegeCurrentLimit;
            $bytegeCurrentLimit !='' && $row['bytegeCurrentLimit']=$bytegeCurrentLimit;
            $TemperatureHighLimit !='' && $row['TemperatureHighLimit']=$TemperatureHighLimit;
            $TemperatureLowLimit !='' && $row['TemperatureLowLimit']=$TemperatureLowLimit;
            $HumiH !='' && $row['HumiH']=$HumiH;
            $HumiL !='' && $row['HumiL']=$HumiL;
            $TemperatureAdjust !='' && $row['TemperatureAdjust']=$TemperatureAdjust;
            $HumiAdjust !='' && $row['HumiAdjust']=$HumiAdjust;

            $insql = Utils::buildInsertSQL($row);
            $sql = "INSERT INTO {{station_parameter}} ".$insql;
            $exec = Yii::app()->bms->createCommand($sql)->execute();
            if ($exec) {
                $ret['data'] = array(
                    'sid' =>$sid,
                );
            }
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '没有站参数数据！'
            );
        }
        echo json_encode($ret);
    }
    public function actionUpdate()
    {
        $sid = Yii::app()->request->getParam('sid' ,0);
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();

        $station_sn_key=Yii::app()->request->getParam('station_sn_key','');
        $MAC_address=Yii::app()->request->getParam('MAC_address','');
        $N_Groups_Incide=Yii::app()->request->getParam('N_Groups_Incide','');
        $Time_interval_Rin=Yii::app()->request->getParam('Time_interval_Rin','');
        $Time_interval_U=Yii::app()->request->getParam('Time_interval_U','');
        $U_abnormal_limit=Yii::app()->request->getParam('U_abnormal_limit','');
        $T_abnormal_limit=Yii::app()->request->getParam('T_abnormal_limit','');
        $Rin_abnormal_limit=Yii::app()->request->getParam('Rin_abnormal_limit','');
        $T_upper_limit=Yii::app()->request->getParam('T_upper_limit','');
        $T_lower_limit=Yii::app()->request->getParam('T_lower_limit','');
        $Humi_upper_limit=Yii::app()->request->getParam('Humi_upper_limit','');
        $Humi_lower_limit=Yii::app()->request->getParam('Humi_lower_limit','');
        $Group_I_criterion=Yii::app()->request->getParam('Group_I_criterion','');
        $bytegeStatus_U_upper=Yii::app()->request->getParam('bytegeStatus_U_upper','');
        $bytegeStatus_U_lower=Yii::app()->request->getParam('bytegeStatus_U_lower','');
        $FloatingbytegeStatus_U_upper=Yii::app()->request->getParam('FloatingbytegeStatus_U_upper','');
        $FloatingbytegeStatus_U_lower=Yii::app()->request->getParam('FloatingbytegeStatus_U_lower','');
        $DisbytegeStatus_U_upper=Yii::app()->request->getParam('DisbytegeStatus_U_upper','');
        $DisbytegeStatus_U_lower=Yii::app()->request->getParam('DisbytegeStatus_U_lower','');
        $N_Groups_Incide_Station=Yii::app()->request->getParam('N_Groups_Incide_Station','');
        $HaveCurrentSensor=Yii::app()->request->getParam('HaveCurrentSensor','');
        $StationCurrentSensorSpan=Yii::app()->request->getParam('StationCurrentSensorSpan','');
        $StationCurrentSensorZeroADCode=Yii::app()->request->getParam('StationCurrentSensorZeroADCode','');
        $OSC=Yii::app()->request->getParam('OSC','');
        $DisbytegeCurrentLimit=Yii::app()->request->getParam('DisbytegeCurrentLimit','');
        $bytegeCurrentLimit=Yii::app()->request->getParam('bytegeCurrentLimit','');
        $TemperatureHighLimit=Yii::app()->request->getParam('TemperatureHighLimit','');
        $TemperatureLowLimit=Yii::app()->request->getParam('TemperatureLowLimit','');
        $HumiH=Yii::app()->request->getParam('HumiH','');
        $HumiL=Yii::app()->request->getParam('HumiL','');
        $TemperatureAdjust=Yii::app()->request->getParam('TemperatureAdjust','');
        $HumiAdjust=Yii::app()->request->getParam('HumiAdjust','');
        if ($station_sn_key) {
            $row = array();
            $station_sn_key !='' && $row['station_sn_key']=$station_sn_key;
            $MAC_address !='' && $row['MAC_address']=$MAC_address;
            $sid !='' && $row['sid']=$sid;
            $N_Groups_Incide !='' && $row['N_Groups_Incide']=$N_Groups_Incide;
            $Time_interval_Rin !='' && $row['Time_interval_Rin']=$Time_interval_Rin;
            $Time_interval_U !='' && $row['Time_interval_U']=$Time_interval_U;
            $U_abnormal_limit !='' && $row['U_abnormal_limit']=$U_abnormal_limit;
            $T_abnormal_limit !='' && $row['T_abnormal_limit']=$T_abnormal_limit;
            $Rin_abnormal_limit !='' && $row['Rin_abnormal_limit']=$Rin_abnormal_limit;
            $T_upper_limit !='' && $row['T_upper_limit']=$T_upper_limit;
            $T_lower_limit !='' && $row['T_lower_limit']=$T_lower_limit;
            $Humi_upper_limit !='' && $row['Humi_upper_limit']=$Humi_upper_limit;
            $Humi_lower_limit !='' && $row['Humi_lower_limit']=$Humi_lower_limit;
            $Group_I_criterion !='' && $row['Group_I_criterion']=$Group_I_criterion;
            $bytegeStatus_U_upper !='' && $row['bytegeStatus_U_upper']=$bytegeStatus_U_upper;
            $bytegeStatus_U_lower !='' && $row['bytegeStatus_U_lower']=$bytegeStatus_U_lower;
            $FloatingbytegeStatus_U_upper !='' && $row['FloatingbytegeStatus_U_upper']=$FloatingbytegeStatus_U_upper;
            $FloatingbytegeStatus_U_lower !='' && $row['FloatingbytegeStatus_U_lower']=$FloatingbytegeStatus_U_lower;
            $DisbytegeStatus_U_upper !='' && $row['DisbytegeStatus_U_upper']=$DisbytegeStatus_U_upper;
            $DisbytegeStatus_U_lower !='' && $row['DisbytegeStatus_U_lower']=$DisbytegeStatus_U_lower;
            $N_Groups_Incide_Station !='' && $row['N_Groups_Incide_Station']=$N_Groups_Incide_Station;
            $HaveCurrentSensor !='' && $row['HaveCurrentSensor']=$HaveCurrentSensor;
            $StationCurrentSensorSpan !='' && $row['StationCurrentSensorSpan']=$StationCurrentSensorSpan;
            $StationCurrentSensorZeroADCode !='' && $row['StationCurrentSensorZeroADCode']=$StationCurrentSensorZeroADCode;
            $OSC !='' && $row['OSC']=$OSC;
            $DisbytegeCurrentLimit !='' && $row['DisbytegeCurrentLimit']=$DisbytegeCurrentLimit;
            $bytegeCurrentLimit !='' && $row['bytegeCurrentLimit']=$bytegeCurrentLimit;
            $TemperatureHighLimit !='' && $row['TemperatureHighLimit']=$TemperatureHighLimit;
            $TemperatureLowLimit !='' && $row['TemperatureLowLimit']=$TemperatureLowLimit;
            $HumiH !='' && $row['HumiH']=$HumiH;
            $HumiL !='' && $row['HumiL']=$HumiL;
            $TemperatureAdjust !='' && $row['TemperatureAdjust']=$TemperatureAdjust;
            $HumiAdjust !='' && $row['HumiAdjust']=$HumiAdjust;

            $upsql = Utils::buildUpdateSQL($row);
            $sql = "update {{station_parameter}} set ".$upsql." where station_sn_key=".$station_sn_key;
            $exec = Yii::app()->bms->createCommand($sql)->execute();
            if ($exec) {
                $ret['data'] = array(
                    'station_sn_key'=>$station_sn_key,
                    'sid' =>$sid,
                );
            }
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '没有站参数数据！'
            );
        }
        echo json_encode($ret);
    }

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}