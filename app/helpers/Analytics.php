<?php namespace Helpers;
	
	/**
	 * Analytics
	 *
	 * Classe che sfrutta le potenzionalità di GAPI
	 * per la visualizzazione delle statistiche di 
	 * Google Analytics.
	 *
	 * @author Davide Cesarano
	 */
	
	use gapi;
    use Core\Config;
	
	class Analytics{
		
		/**
         * @var object $ga
         */
        private $ga;
        
        /**
         * @var int $profile_id
         */
        private $profile_id;
        
        /**
         * Attiva oggetto $ga
         *
         * @param string GA_SERVICE_ACCOUNT
         * @param string GA_SERVICE_KEY
         * @return object $ga
         */
		public function __construct(){
			
            $service_account = Config::get('ga', 'service_account');
            $service_key = Config::get('ga', 'service_key');
            $this->profile_id = Config::get('ga', 'profile_id');
            $this->ga = new gapi($service_account, $service_key);
		
        }
		
		/**
		 * Visite
         *
         * @param int GA_PROFILE_ID
         * @return array $data
		 */
		public function visits(){
			
			// data attuale
			$today = date('Y-m-d');
			
			// periodo
			$daysAgo = date('Y-m-d', strtotime('-30 day', strtotime($today)));
			
			// richiesta dati
			$this->ga->requestReportData(
				$this->profile_id,
				array('year', 'month', 'day'), 
				array('visits'), 
				$sort_metric = '-year', 
				$filter = null,
				$start_date = $daysAgo, 
				$end_date = 'yesterday', 
				$start_index = 1, 
				$max_results = 31
			);
			
			$data = array();
			$i = 0;
			foreach($this->ga->getResults() as $result){
				$data[$i]['period'] = date('Y-m-d', strtotime('+'.$i.' day', strtotime($daysAgo)));
				$data[$i]['visits'] = $result->getVisits();
				$i++;
			}
			return $data;
		
		}
        
        /**
		 * Totali ultimi 30 giorni
		 *
         * @param int GA_PROFILE_ID
		 * @return array $data
		 */
		public function total30daysAgo(){
			
			// data attuale
			$today = date('Y-m-d');
			
			// periodo
			$daysAgo = date('Y-m-d', strtotime('-30 day', strtotime($today)));
			
			// richiesta dati
			$this->ga->requestReportData(
				$this->profile_id,
				array('year', 'month', 'day'), 
				array('visits', 'Visitors', 'pageviews', 'pageviewsPerSession', 'SessionDuration', 'bounceRate', 'percentNewSessions'), 
				$sort_metric = '-year', 
				$filter = null, 
				$start_date = $daysAgo, 
				$end_date = 'yesterday', 
				$start_index = 1, 
				$max_results = 31
			);
			
			$data = array();
			$i = 0;
			foreach($this->ga->getResults() as $result){
				$data['sessions'] = number_format($this->ga->getVisits(), 0, '', '.');
				$data['users'] = number_format($this->ga->getVisitors(), 0, '', '.');
				$data['pageviews'] = number_format($this->ga->getPageviews(), 0, '', '.');
				$data['pageviewsPerSession'] = number_format($this->ga->getPageviewsPerSession(), 2, ',', '.');
				$data['SessionDuration'] = gmdate('H:i:s', ((($this->ga->getSessionDuration() / $this->ga->getVisits()) / (24*60*60)) - 25569) * 86400);
				$data['bounceRate'] = number_format($this->ga->getBounceRate(), 2, ',', '.');
				$data['percentNewSessions'] = number_format($this->ga->getPercentNewSessions(), 2, ',', '.');
				$i++;
			}
			return $data;
			
		}
		
		/**
		 * Browser
		 *
         * @param int GA_PROFILE_ID
		 * @param int $max
		 * @return array $data
		 */
		public function browser($max){
			
			// data attuale
			$today = date('Y-m-d');
			
			// periodo
			$daysAgo = date('Y-m-d', strtotime('-30 day', strtotime($today)));
			
			// metriche
			$metric = str_replace('.', '', $this->total30daysAgo());
			
			// richiesta dati
			$this->ga->requestReportData(
				$this->profile_id,
				array('browser'), 
				array('visits'), 
				$sort_metric = '-visits', 
				$filter = null, 
				$start_date = $daysAgo, 
				$end_date = 'yesterday', 
				$start_index = 1, 
				$max_results = $max
			);
			
			// risultato
			$data = array();
			$i = 0;
			foreach($this->ga->getResults() as $result){
				$data[$i]['name'] = $result->getBrowser();
				$data[$i]['visits'] = number_format((($result->getVisits() / $metric['sessions']) * 100), 2, ',', '.').'%';
				$i++;
			}
			return $data;
			
		}
		
		/**
		 * Sistema Operativo
		 *
         * @param int GA_PROFILE_ID
		 * @param int $max
		 * @return array $data
		 */
		public function operatingSystem($max){
			
			// data attuale
			$today = date('Y-m-d');
			
			// periodo
			$daysAgo = date('Y-m-d', strtotime('-30 day', strtotime($today)));
			
			// metriche
			$metric = str_replace('.', '', $this->total30daysAgo());
			
			// richiesta dati
			$this->ga->requestReportData(
				$this->profile_id,
				array('operatingSystem'), 
				array('visits'), 
				$sort_metric = '-visits', 
				$filter = 'operatingSystem!=(not set)', 
				$start_date = $daysAgo, 
				$end_date = 'yesterday', 
				$start_index = 1, 
				$max_results = $max
			);
			
			// risultato
			$data = array();
			$i = 0;
			foreach($this->ga->getResults() as $result){
				$data[$i]['name'] = $result->getOperatingSystem();
				$data[$i]['visits'] = number_format((($result->getVisits() / $metric['sessions']) * 100), 2, ',', '.').'%';
				$i++;
			}
			return $data;
			
		}
		
		/**
		 * Città
		 *
         * @param int GA_PROFILE_ID
		 * @param int $max
		 * @return array $data
		 */
		public function city($max){
			
			// data attuale
			$today = date('Y-m-d');
			
			// periodo
			$daysAgo = date('Y-m-d', strtotime('-30 day', strtotime($today)));
			
			// metriche
			$metric = str_replace('.', '', $this->total30daysAgo());
			
			// richiesta dati
			$this->ga->requestReportData(
				$this->profile_id,
				array('city'), 
				array('visits'), 
				$sort_metric = '-visits', 
				$filter = 'city!=(not set)', 
				$start_date = $daysAgo, 
				$end_date = 'yesterday', 
				$start_index = 1, 
				$max_results = $max
			);
			
			// risultato
			$data = array();
			$i = 0;
			foreach($this->ga->getResults() as $result){
				$data[$i]['name'] = $result->getCity();
				$data[$i]['visits'] = number_format((($result->getVisits() / $metric['sessions']) * 100), 2, ',', '.').'%';
				$i++;
			}
			return $data;
			
		}
		
		/**
		 * Paese
		 *
         * @param int GA_PROFILE_ID
		 * @param int $max
		 * @return array $data
		 */
		public function country($max){
			
			// data attuale
			$today = date('Y-m-d');
			
			// periodo
			$daysAgo = date('Y-m-d', strtotime('-30 day', strtotime($today)));
			
			// metriche
			$metric = str_replace('.', '', $this->total30daysAgo());
			
			// richiesta dati
			$this->ga->requestReportData(
				$this->profile_id,
				array('country'), 
				array('visits'), 
				$sort_metric = '-visits', 
				$filter = 'country!=(not set)', 
				$start_date = $daysAgo, 
				$end_date = 'yesterday', 
				$start_index = 1, 
				$max_results = $max
			);
			
			// risultato
			$data = array();
			$i = 0;
			foreach($this->ga->getResults() as $result){
				$data[$i]['name'] = $result->getCountry();
				$data[$i]['visits'] = number_format((($result->getVisits() / $metric['sessions']) * 100), 2, ',', '.').'%';
				$i++;
			}
			return $data;
			
		}

	}