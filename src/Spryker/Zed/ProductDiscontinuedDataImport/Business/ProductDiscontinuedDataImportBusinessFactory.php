<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductDiscontinuedDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\ProductDiscontinuedDataImport\Business\ProductDiscontinuedImportStep\ConcreteSkuToIdProductStep;
use Spryker\Zed\ProductDiscontinuedDataImport\Business\ProductDiscontinuedImportStep\NoteExtractorStep;
use Spryker\Zed\ProductDiscontinuedDataImport\Business\ProductDiscontinuedImportStep\ProductDiscontinuedWriterStep;

/**
 * @method \Spryker\Zed\ProductDiscontinuedDataImport\ProductDiscontinuedDataImportConfig getConfig()
 */
class ProductDiscontinuedDataImportBusinessFactory extends DataImportBusinessFactory
{
    public function createProductDiscontinuedDataImport(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterInterface {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporter $dataImporter */
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $dataImporterConfigurationTransfer ?? $this->getConfig()->getProductDiscontinuedDataImporterConfiguration(),
        );

        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware $dataSetStepBroker */
        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createAddLocalesStep())
            ->addStep($this->createNoteExtractorStep())
            ->addStep($this->createConcreteSkuToIdProductStep())
            ->addStep(new ProductDiscontinuedWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    public function createNoteExtractorStep(): DataImportStepInterface
    {
        return new NoteExtractorStep();
    }

    public function createConcreteSkuToIdProductStep(): DataImportStepInterface
    {
        return new ConcreteSkuToIdProductStep();
    }
}
