<?php

class Consulta extends Conexao
{
	// categoria dos materiais
	private $categoria;

	function __construct()
	{
		
	}

	// funcao para consultar materiais
	public function consultar()
	{
		// verifica se conectou com o banco
		if (parent::__construct()):

			// grupo dos materiais
			// grupo padrão 001 - MEDICAMENTOS
			$grupo = $_GET['grupo'] ?? 001;

			// consulta os medicamentos
			$query_mes = $this->db_connection->prepare("SELECT 
				[MESATU] = LEFT(DATENAME(MONTH, DATEADD(MONTH, 0, GETDATE())),3),
				[MESANT] = LEFT(DATENAME(MONTH, DATEADD(MONTH, -1, GETDATE())),3),
				[MESPAS] = LEFT(DATENAME(MONTH, DATEADD(MONTH, -2, GETDATE())),3)");
			$query_mes->execute();
			
			// armazena resultado dos meses
			$this->result_mes = $query_mes->fetchObject();
			
			// consulta os medicamentos
			// fórmula 1 e 2 feitas pelo próprio farmacêutico de acordo com as necessidades do setor
			$query_material = $this->db_connection->prepare("SELECT CODMAT, DESCRI, CURVAC, CATEGO, ESTOQU, CONSUM, SUFICI, SETEDI, MESATU, MESPAS, MESANT, CODIGO, FORMULA1, FORMULA2, 
			CASE
				WHEN (FORMULA1 >= 2 AND FORMULA1 < 3 AND FORMULA2 < 15) THEN '&#128293;'
				WHEN (FORMULA1 >= 3 AND FORMULA1 < 4 AND FORMULA2 < 15) THEN '&#128293;&#128293;'
				WHEN (FORMULA1 >= 4 AND FORMULA2 < 15) THEN '&#128293;&#128293;&#128293;'
			END AS RESULTADO
			FROM
			(
			SELECT
				[CODMAT] = M.MAT_COD,
				[DESCRI] = M.MAT_DESC_COMPLETA,
				[CURVAC] = M.MAT_IND_CURVA_ABC,
				[CATEGO] = L.LMA_NOME,
				[ESTOQU] = SUM(E.ETQ_QUANTIDADE),
				[CONSUM] = (SELECT COALESCE(ROUND(SUM(((MMA_QTD*MMA_TIPO_ES_FATOR)*-1)/3),1),0) FROM MMA WITH(NOLOCK) JOIN MAT WITH(NOLOCK) ON MAT_COD = MMA_MAT_COD WHERE MMA_TIPO_OPERACAO IN ('S1', 'S2', 'E4', 'S8') AND MMA_DATA_MOV BETWEEN DATEADD(DAY, -90, GETDATE()) AND GETDATE() AND MAT_GMM_COD = ? AND MAT_DEL_LOGICA = 'N' AND MAT_IND_PADRONIZADO = 'S' AND MMA_MAT_COD = M.MAT_COD),	
				[SUFICI] = ISNULL((SUM(E.ETQ_QUANTIDADE)*30)/(SELECT NULLIF(SUM((MMA_QTD*MMA_TIPO_ES_FATOR)*-1)/3,0) FROM MMA WITH(NOLOCK) JOIN MAT WITH(NOLOCK) ON MAT_COD = MMA_MAT_COD WHERE MMA_TIPO_OPERACAO IN ('S1','S2','E4','S8') AND MMA_DATA_MOV BETWEEN DATEADD(DAY, -90, GETDATE()) AND GETDATE() AND MAT_GMM_COD = ? AND MAT_DEL_LOGICA = 'N' AND MAT_IND_PADRONIZADO = 'S' AND MMA_MAT_COD = M.MAT_COD),0),
				[SETEDI] = (SELECT COALESCE(SUM((MMA_QTD * MMA_TIPO_ES_FATOR)*-1),0) FROM MMA WITH(NOLOCK) WHERE MMA_MAT_COD = M.MAT_COD AND MMA_DATA_MOV BETWEEN DATEADD(DAY, -7, GETDATE()) AND GETDATE() AND MMA_TIPO_OPERACAO IN ('S1','S2','E4','S8')),
				[MESATU] = (SELECT SUM(MMA_QTD * MMA_TIPO_ES_FATOR) * -1 FROM MMA WITH (NOLOCK) JOIN MAT WITH (NOLOCK) ON MAT_COD = MMA_MAT_COD WHERE MMA_TIPO_OPERACAO IN ('S1', 'S2', 'E4', 'S8') AND MMA_DATA_MOV BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0) AND DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())+1, 0) AND MAT_GMM_COD = ? AND MAT_DEL_LOGICA = 'N' AND MAT_IND_PADRONIZADO = 'S' AND MMA_MAT_COD = M.MAT_COD GROUP BY MMA_MAT_COD),
				[MESPAS] = (SELECT SUM(MMA_QTD * MMA_TIPO_ES_FATOR) * -1 FROM MMA WITH (NOLOCK) JOIN MAT WITH (NOLOCK) ON MAT_COD = MMA_MAT_COD WHERE MMA_TIPO_OPERACAO IN ('S1', 'S2', 'E4', 'S8') AND MMA_DATA_MOV BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-1, 0) AND DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0) AND MAT_GMM_COD = ? AND MAT_DEL_LOGICA = 'N' AND MAT_IND_PADRONIZADO = 'S' AND MMA_MAT_COD = M.MAT_COD GROUP BY MMA_MAT_COD),
				[MESANT] = (SELECT SUM(MMA_QTD * MMA_TIPO_ES_FATOR) * -1 FROM MMA WITH (NOLOCK) JOIN MAT WITH (NOLOCK) ON MAT_COD = MMA_MAT_COD WHERE MMA_TIPO_OPERACAO IN ('S1', 'S2', 'E4', 'S8') AND MMA_DATA_MOV BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-2, 0) AND DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-1, 0) AND MAT_GMM_COD = ? AND MAT_DEL_LOGICA = 'N' AND MAT_IND_PADRONIZADO = 'S' AND MMA_MAT_COD = M.MAT_COD GROUP BY MMA_MAT_COD),
				[CODIGO] = (SELECT CASE ISNUMERIC(FIC_MAT_COD) WHEN 1 THEN '<a onclick=\"loadModal('+CONVERT(VARCHAR(10), FIC_MAT_COD)+')\" class=\"btn btn-default btn-sm\" data-toggle=\"modal\" data-target=\"#1\"><i class=\"fa fa-shopping-cart\"></i></a>' ELSE '' END FROM FIC WITH (NOLOCK) WHERE FIC_DT_REG_COMPRA >= (DATEADD(MONTH, - 2, GETDATE())) AND FIC_MAT_COD = M.MAT_COD AND FIC_DEL_LOGICA = 'N' AND FIC_AFT_SERIE IS NOT NULL AND FIC_QTDE_PENDENTE <> 0 GROUP BY FIC_MAT_COD),
				[FORMULA1] = ( (30 * (SELECT COALESCE(SUM((MMA_QTD * MMA_TIPO_ES_FATOR)*-1),0) FROM MMA WITH(NOLOCK) WHERE MMA_MAT_COD = M.MAT_COD AND MMA_DATA_MOV BETWEEN DATEADD(DAY, -7, GETDATE()) AND GETDATE() AND MMA_TIPO_OPERACAO IN ('S1','S2','E4','S8'))) / (7 * (SELECT NULLIF(COALESCE(ROUND(SUM(((MMA_QTD*MMA_TIPO_ES_FATOR)*-1)/3),1),0),0) FROM MMA WITH(NOLOCK) JOIN MAT WITH(NOLOCK) ON MAT_COD = MMA_MAT_COD WHERE MMA_TIPO_OPERACAO IN ('S1', 'S2', 'E4', 'S8') AND MMA_DATA_MOV BETWEEN DATEADD(DAY, -90, GETDATE()) AND GETDATE() AND MAT_GMM_COD = ? AND MAT_DEL_LOGICA = 'N' AND MAT_IND_PADRONIZADO = 'S' AND MMA_MAT_COD = M.MAT_COD)) ),
				[FORMULA2] = (SUM(E.ETQ_QUANTIDADE) * 7) / (SELECT NULLIF(COALESCE(SUM((MMA_QTD * MMA_TIPO_ES_FATOR)*-1),0),0) FROM MMA WITH(NOLOCK) WHERE MMA_MAT_COD = M.MAT_COD AND MMA_DATA_MOV BETWEEN DATEADD(DAY, -7, GETDATE()) AND GETDATE() AND MMA_TIPO_OPERACAO IN ('S1','S2','E4','S8'))
			FROM
				MAT M WITH(NOLOCK)
				JOIN ETQ E WITH(NOLOCK) ON M.MAT_COD = E.ETQ_MAT_COD
				JOIN LMA L WITH(NOLOCK) ON M.MAT_LMA_COD = L.LMA_COD
			WHERE
				M.MAT_GMM_COD = ? AND
				M.MAT_DEL_LOGICA = 'N' AND
				L.LMA_GMM_COD = ? AND
				M.MAT_IND_PADRONIZADO = 'S'
			GROUP BY
				M.MAT_COD,
				M.MAT_DESC_COMPLETA,
				M.MAT_IND_CURVA_ABC,
				L.LMA_NOME
			) XYZ");
			$query_material->bindValue(1, $grupo, PDO::PARAM_INT);
			$query_material->bindValue(2, $grupo, PDO::PARAM_INT);
			$query_material->bindValue(3, $grupo, PDO::PARAM_INT);
			$query_material->bindValue(4, $grupo, PDO::PARAM_INT);
			$query_material->bindValue(5, $grupo, PDO::PARAM_INT);
			$query_material->bindValue(6, $grupo, PDO::PARAM_INT);
			$query_material->bindValue(7, $grupo, PDO::PARAM_INT);
			$query_material->bindValue(8, $grupo, PDO::PARAM_INT);
			$query_material->execute();
			
			// se retorna algo, armazena no array
			if ($query_material->rowCount()):
				while ($result_row = $query_material->fetchObject()):
					$this->result[] = $result_row;
				endwhile;
				return true;			
			endif;			
		else:
			echo "Erro ao conectar o banco de dados.";
		endif;
	}

	// consultar solicitações pendentes
	public function consultarAF()
	{
		// verifica se conectou com o banco
		if (parent::__construct()):
			
			// recebe o código do material
			$codigo = $_GET['codigo'];

			// consulta os medicamentos
			$query_af = $this->db_connection->prepare("SELECT
					FIC_AFT_SERIE,
					FIC_AFT_NUM,
					FIC_MAT_COD,
					FNE_NOME_FANTASIA,
					[COMPRA] = CONVERT(VARCHAR, FIC_DT_REG_COMPRA, 103),
					FIC_QTDE_COTADA,
					FIC_QTDE_PENDENTE,
					[PREVISAO] = CONVERT(VARCHAR, FIC_DT_PREV_ENTREGA, 103)
				FROM
					FIC WITH (NOLOCK)
					JOIN FNE WITH (NOLOCK) ON FNE_COD = FIC_FNE_COD
				WHERE
					FIC_DT_REG_COMPRA >= (DATEADD(MONTH, - 2, GETDATE()))
					AND FIC_MAT_COD = :codigo
					AND FIC_DEL_LOGICA = 'N'
					AND FIC_AFT_SERIE IS NOT NULL
					AND FIC_QTDE_PENDENTE <> 0
				GROUP BY
					FIC_MAT_COD,
					FIC_QTDE_COTADA,
					FIC_AFT_SERIE,
					FIC_AFT_NUM,
					FIC_DT_REG_COMPRA,
					FIC_QTDE_PENDENTE,
					FIC_DT_PREV_ENTREGA,
					FNE_NOME_FANTASIA");
			$query_af->bindValue(':codigo', $codigo, PDO::PARAM_INT);
			$query_af->execute();
		
			// se retorna algo, armazena no array
			if ($query_af->rowCount()):
				while ($result_af = $query_af->fetchObject()):
					$this->resultAF[] = $result_af;
				endwhile;
				return true;			
			endif;
		else:
			echo "Erro ao conectar o banco de dados.";
		endif;		
	}

	// formatar número com 1 casa decimal
	public function formatarNumero($num)
	{
		return number_format($num, 1, ',', '');
	}

	// formatar número com 1 casa decimal
	public function arredonda($num)
	{
		return number_format($num, 0, ',', '');
	}

}