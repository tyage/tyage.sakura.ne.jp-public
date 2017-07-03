<?php
// TODO: 職のマスター処理
class JobsController extends AppController{
	var $uses = array('Job','User','UserJob');

	var $interval = 10;//（分）
	var $point = 100;
	var $levelBonus = 1.1; //一レベル上がるごとに貰える給料や次レベルまでのポイントの倍率
	var $masterLevel = 20;

	function index() {
		$this->set('jobs', $this->paginate());
		$this->set('abilities', $this->User->abilities);
	}

	function get() {
		$job = $this->Job->findById($this->data['Job']['id']);
		if (empty($job)) {
			$this->UserJob->invalidate('UserJob.job_id');
		}

		$user = $this->Self->load();

		// 経歴調査
		if ($job['Job']['career'] > 0) {
			$condition = array(
				'User.id' => $this->Self->id,
				'Job.id' => $job['Job']['career']
			);
			$userJob = $this->UserJob->find('first', $condition);
			if ($userJob['Job']['id'] != $job['Job']['career']) {
				$this->UserJob->invalidate('Job.career');
			}
		}

		// BMI検査
		$bmi = $this->User->getBMI($user);
		if ($job['Job']['bmi_min'] > 0 and $job['Job']['bmi_min'] > $bmi) {
			$this->UserJob->invalidate('Job.bmi_min');
		}
		if ($job['Job']['bmi_max'] > 0 and $job['Job']['bmi_max'] < $bmi) {
			$this->UserJob->invalidate('Job.bmi_max');
		}

		// 性別検査
		if ($job['Job']['sex'] != -1 and $job['Job']['sex'] != $user['Profile']['sex']) {
			$this->UserJob->invalidate('Job.sex');
		}

		// 能力値検査
		foreach ($this->User->abilities as $ability) {
			if ($ability === 'energy' or $ability === 'spirit') {
				continue;
			}
			if($job['Job'][$ability] > $user['Profile'][$ability]) {
				$this->UserJob->invalidate('Job'.$ability);
			}
		}

		$condition = array(
			'conditions' => array(
				'User.id' => $this->Self->id,
				'Job.id' => $job['Job']['id']
			)
		);
		$userJob = $this->UserJob->find('first', $condition);

		$this->UserJob->id = $userJob['UserJob']['id'];
		$userJob['UserJob']['user_id'] = $this->Self->id;
		$userJob['UserJob']['job_id'] = $job['Job']['id'];
		if ($this->UserJob->save($userJob)) {
			$user = array();
			$user['Profile']['user_job_id'] =
				empty($userJob['UserJob']['id']) ?
				$this->UserJob->getLastInsertID() :
				$userJob['UserJob']['id'];
			$this->Self->saveProfile($user);
		}

		$this->redirect('/jobs/');
	}

	function work() {
		$user = $this->Self->load(array('Profile.health','Profile.money','Profile.user_job_id','Profile.energy','Profile.spirit'));
		$userJob = $this->UserJob->findById($user['Profile']['user_job_id']);
		$job = $this->Job->findById($userJob['Job']['id']);

		// 健康検査
		if ($user['Profile']['health'] < 0) {
			return false;
		}

		// 体力、精神力検査
		$user['Profile']['energy'] += $job['Job']['energy'];
		$user['Profile']['spirit'] += $job['Job']['spirit'];
		if ($user['Profile']['energy'] < 0 or $user['Profile']['spirit'] < 0) {
			return false;
		}

		// 時間検査
		if ($userJob['UserJob']['updated'] > date('Y-m-d H:i:s', time() - $this->interval)) {
			return false;
		}
		$userJob['UserJob']['updated'] = date('Y-m-d H:i:s');

		// 経験値、仕事回数増加
		$point = $this->_calcPoint($user['Profile']['health']);
		$level = $this->_calcLevel($userJob['UserJob']['point']);
		$userJob['UserJob']['point'] += $point;
		$userJob['UserJob']['count']++;
		$this->UserJob->save($userJob);

		$this->set('point', $point);
		$this->set('userJob', $userJob);

		// レベルアップ
		$maxPoint = $this->_calcMaxPoint($level);
		$rest = $maxPoint - $userJob['UserJob']['point'];
		if ($rest <= 0) {
			++$level;
			$this->set('levelUp', true);
		}

		$this->set('level', $level);
		$this->set('maxPoint', $maxPoint);

		// 給料ゲット
		$rest = $userJob['UserJob']['count'] % $job['Salary']['span'];
		if ($rest == 0) {
			$salary = $job['Job']['salary'] * pow($this->levelBonus, $level) * $job['Salary']['span'];
			$user['Profile']['money'] += $salary;
			$this->set('salary', $salary);
		}
		$this->set('rest', $rest);

		$this->Self->saveProfile($user);
	}

	function _calcPoint($health) {
		return $health + rand($health / 2 * -1,$health / 2);
	}

	function _calcMaxPoint($level) {
		// Σ point*bonus^n(n = 1..level)
		return $this->point + $this->point * $this->levelBonus *
			(pow($this->levelBonus, $level) - 1) / ($this->levelBonus - 1);
	}

	function _calcLevel($point) {
		$level = 0;
		while (1) {
			if ($point <= $this->_calcMaxPoint($level)) {
				return $level;
			}
			$level++;
		}
	}
}
