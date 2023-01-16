<?php

namespace App\Services\Analytics\Domain;

use App\Services\Observability\DomainMetrics;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Domain service: KpiAggregator
 * Module: Analytics
 *
 * Encapsulates operational workflows for airline Analytics processes.
 */
class KpiAggregator
{
    protected $logger;
    protected DomainMetrics $metrics;

    public function __construct(DomainMetrics $metrics)
    {
        $this->logger = Log::channel('operations');
        $this->metrics = $metrics;
    }

    public function execute(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.execute.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'execute');
            if (!$pre['ok']) {
                return $this->failure('execute', $pre['errors'], $startedAt);
            }

            $result = $this->perform('execute', $normalized);
            $this->afterHook('execute', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.execute.success');

            return $this->success('execute', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.execute.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.execute.failure');
            return $this->failure('execute', [$e->getMessage()], $startedAt);
        }
    }

    public function validate(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.validate.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'validate');
            if (!$pre['ok']) {
                return $this->failure('validate', $pre['errors'], $startedAt);
            }

            $result = $this->perform('validate', $normalized);
            $this->afterHook('validate', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.validate.success');

            return $this->success('validate', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.validate.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.validate.failure');
            return $this->failure('validate', [$e->getMessage()], $startedAt);
        }
    }

    public function simulate(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.simulate.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'simulate');
            if (!$pre['ok']) {
                return $this->failure('simulate', $pre['errors'], $startedAt);
            }

            $result = $this->perform('simulate', $normalized);
            $this->afterHook('simulate', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.simulate.success');

            return $this->success('simulate', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.simulate.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.simulate.failure');
            return $this->failure('simulate', [$e->getMessage()], $startedAt);
        }
    }

    public function reconcile(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.reconcile.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'reconcile');
            if (!$pre['ok']) {
                return $this->failure('reconcile', $pre['errors'], $startedAt);
            }

            $result = $this->perform('reconcile', $normalized);
            $this->afterHook('reconcile', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.reconcile.success');

            return $this->success('reconcile', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.reconcile.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.reconcile.failure');
            return $this->failure('reconcile', [$e->getMessage()], $startedAt);
        }
    }

    public function optimize(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.optimize.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'optimize');
            if (!$pre['ok']) {
                return $this->failure('optimize', $pre['errors'], $startedAt);
            }

            $result = $this->perform('optimize', $normalized);
            $this->afterHook('optimize', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.optimize.success');

            return $this->success('optimize', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.optimize.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.optimize.failure');
            return $this->failure('optimize', [$e->getMessage()], $startedAt);
        }
    }

    public function forecast(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.forecast.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'forecast');
            if (!$pre['ok']) {
                return $this->failure('forecast', $pre['errors'], $startedAt);
            }

            $result = $this->perform('forecast', $normalized);
            $this->afterHook('forecast', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.forecast.success');

            return $this->success('forecast', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.forecast.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.forecast.failure');
            return $this->failure('forecast', [$e->getMessage()], $startedAt);
        }
    }

    public function publish(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.publish.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'publish');
            if (!$pre['ok']) {
                return $this->failure('publish', $pre['errors'], $startedAt);
            }

            $result = $this->perform('publish', $normalized);
            $this->afterHook('publish', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.publish.success');

            return $this->success('publish', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.publish.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.publish.failure');
            return $this->failure('publish', [$e->getMessage()], $startedAt);
        }
    }

    public function rollback(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.rollback.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'rollback');
            if (!$pre['ok']) {
                return $this->failure('rollback', $pre['errors'], $startedAt);
            }

            $result = $this->perform('rollback', $normalized);
            $this->afterHook('rollback', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.rollback.success');

            return $this->success('rollback', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.rollback.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.rollback.failure');
            return $this->failure('rollback', [$e->getMessage()], $startedAt);
        }
    }

    public function snapshot(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.snapshot.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'snapshot');
            if (!$pre['ok']) {
                return $this->failure('snapshot', $pre['errors'], $startedAt);
            }

            $result = $this->perform('snapshot', $normalized);
            $this->afterHook('snapshot', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.snapshot.success');

            return $this->success('snapshot', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.snapshot.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.snapshot.failure');
            return $this->failure('snapshot', [$e->getMessage()], $startedAt);
        }
    }

    public function diff(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.diff.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'diff');
            if (!$pre['ok']) {
                return $this->failure('diff', $pre['errors'], $startedAt);
            }

            $result = $this->perform('diff', $normalized);
            $this->afterHook('diff', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.diff.success');

            return $this->success('diff', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.diff.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.diff.failure');
            return $this->failure('diff', [$e->getMessage()], $startedAt);
        }
    }

    public function enrich(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.enrich.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'enrich');
            if (!$pre['ok']) {
                return $this->failure('enrich', $pre['errors'], $startedAt);
            }

            $result = $this->perform('enrich', $normalized);
            $this->afterHook('enrich', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.enrich.success');

            return $this->success('enrich', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.enrich.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.enrich.failure');
            return $this->failure('enrich', [$e->getMessage()], $startedAt);
        }
    }

    public function normalize(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.normalize.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'normalize');
            if (!$pre['ok']) {
                return $this->failure('normalize', $pre['errors'], $startedAt);
            }

            $result = $this->perform('normalize', $normalized);
            $this->afterHook('normalize', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.normalize.success');

            return $this->success('normalize', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.normalize.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.normalize.failure');
            return $this->failure('normalize', [$e->getMessage()], $startedAt);
        }
    }

    public function aggregate(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.aggregate.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'aggregate');
            if (!$pre['ok']) {
                return $this->failure('aggregate', $pre['errors'], $startedAt);
            }

            $result = $this->perform('aggregate', $normalized);
            $this->afterHook('aggregate', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.aggregate.success');

            return $this->success('aggregate', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.aggregate.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.aggregate.failure');
            return $this->failure('aggregate', [$e->getMessage()], $startedAt);
        }
    }

    public function dispatch(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.dispatch.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'dispatch');
            if (!$pre['ok']) {
                return $this->failure('dispatch', $pre['errors'], $startedAt);
            }

            $result = $this->perform('dispatch', $normalized);
            $this->afterHook('dispatch', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.dispatch.success');

            return $this->success('dispatch', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.dispatch.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.dispatch.failure');
            return $this->failure('dispatch', [$e->getMessage()], $startedAt);
        }
    }

    public function acknowledge(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.acknowledge.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'acknowledge');
            if (!$pre['ok']) {
                return $this->failure('acknowledge', $pre['errors'], $startedAt);
            }

            $result = $this->perform('acknowledge', $normalized);
            $this->afterHook('acknowledge', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.acknowledge.success');

            return $this->success('acknowledge', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.acknowledge.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.acknowledge.failure');
            return $this->failure('acknowledge', [$e->getMessage()], $startedAt);
        }
    }

    public function escalate(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.escalate.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'escalate');
            if (!$pre['ok']) {
                return $this->failure('escalate', $pre['errors'], $startedAt);
            }

            $result = $this->perform('escalate', $normalized);
            $this->afterHook('escalate', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.escalate.success');

            return $this->success('escalate', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.escalate.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.escalate.failure');
            return $this->failure('escalate', [$e->getMessage()], $startedAt);
        }
    }

    public function suppress(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.suppress.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'suppress');
            if (!$pre['ok']) {
                return $this->failure('suppress', $pre['errors'], $startedAt);
            }

            $result = $this->perform('suppress', $normalized);
            $this->afterHook('suppress', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.suppress.success');

            return $this->success('suppress', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.suppress.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.suppress.failure');
            return $this->failure('suppress', [$e->getMessage()], $startedAt);
        }
    }

    public function replay(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.replay.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'replay');
            if (!$pre['ok']) {
                return $this->failure('replay', $pre['errors'], $startedAt);
            }

            $result = $this->perform('replay', $normalized);
            $this->afterHook('replay', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.replay.success');

            return $this->success('replay', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.replay.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.replay.failure');
            return $this->failure('replay', [$e->getMessage()], $startedAt);
        }
    }

    public function hydrate(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.hydrate.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'hydrate');
            if (!$pre['ok']) {
                return $this->failure('hydrate', $pre['errors'], $startedAt);
            }

            $result = $this->perform('hydrate', $normalized);
            $this->afterHook('hydrate', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.hydrate.success');

            return $this->success('hydrate', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.hydrate.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.hydrate.failure');
            return $this->failure('hydrate', [$e->getMessage()], $startedAt);
        }
    }

    public function persist(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.persist.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'persist');
            if (!$pre['ok']) {
                return $this->failure('persist', $pre['errors'], $startedAt);
            }

            $result = $this->perform('persist', $normalized);
            $this->afterHook('persist', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.persist.success');

            return $this->success('persist', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.persist.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.persist.failure');
            return $this->failure('persist', [$e->getMessage()], $startedAt);
        }
    }

    public function invalidate(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.invalidate.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'invalidate');
            if (!$pre['ok']) {
                return $this->failure('invalidate', $pre['errors'], $startedAt);
            }

            $result = $this->perform('invalidate', $normalized);
            $this->afterHook('invalidate', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.invalidate.success');

            return $this->success('invalidate', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.invalidate.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.invalidate.failure');
            return $this->failure('invalidate', [$e->getMessage()], $startedAt);
        }
    }

    public function broadcast(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.broadcast.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'broadcast');
            if (!$pre['ok']) {
                return $this->failure('broadcast', $pre['errors'], $startedAt);
            }

            $result = $this->perform('broadcast', $normalized);
            $this->afterHook('broadcast', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.broadcast.success');

            return $this->success('broadcast', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.broadcast.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.broadcast.failure');
            return $this->failure('broadcast', [$e->getMessage()], $startedAt);
        }
    }

    public function compact(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.compact.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'compact');
            if (!$pre['ok']) {
                return $this->failure('compact', $pre['errors'], $startedAt);
            }

            $result = $this->perform('compact', $normalized);
            $this->afterHook('compact', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.compact.success');

            return $this->success('compact', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.compact.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.compact.failure');
            return $this->failure('compact', [$e->getMessage()], $startedAt);
        }
    }

    public function archiveBatch(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.archiveBatch.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'archiveBatch');
            if (!$pre['ok']) {
                return $this->failure('archiveBatch', $pre['errors'], $startedAt);
            }

            $result = $this->perform('archiveBatch', $normalized);
            $this->afterHook('archiveBatch', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.archiveBatch.success');

            return $this->success('archiveBatch', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.archiveBatch.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.archiveBatch.failure');
            return $this->failure('archiveBatch', [$e->getMessage()], $startedAt);
        }
    }

    public function evaluateRules(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.evaluateRules.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'evaluateRules');
            if (!$pre['ok']) {
                return $this->failure('evaluateRules', $pre['errors'], $startedAt);
            }

            $result = $this->perform('evaluateRules', $normalized);
            $this->afterHook('evaluateRules', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.evaluateRules.success');

            return $this->success('evaluateRules', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.evaluateRules.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.evaluateRules.failure');
            return $this->failure('evaluateRules', [$e->getMessage()], $startedAt);
        }
    }

    public function applyPolicy(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.applyPolicy.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'applyPolicy');
            if (!$pre['ok']) {
                return $this->failure('applyPolicy', $pre['errors'], $startedAt);
            }

            $result = $this->perform('applyPolicy', $normalized);
            $this->afterHook('applyPolicy', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.applyPolicy.success');

            return $this->success('applyPolicy', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.applyPolicy.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.applyPolicy.failure');
            return $this->failure('applyPolicy', [$e->getMessage()], $startedAt);
        }
    }

    public function computeMetrics(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.computeMetrics.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'computeMetrics');
            if (!$pre['ok']) {
                return $this->failure('computeMetrics', $pre['errors'], $startedAt);
            }

            $result = $this->perform('computeMetrics', $normalized);
            $this->afterHook('computeMetrics', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.computeMetrics.success');

            return $this->success('computeMetrics', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.computeMetrics.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.computeMetrics.failure');
            return $this->failure('computeMetrics', [$e->getMessage()], $startedAt);
        }
    }

    public function buildPayload(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.buildPayload.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'buildPayload');
            if (!$pre['ok']) {
                return $this->failure('buildPayload', $pre['errors'], $startedAt);
            }

            $result = $this->perform('buildPayload', $normalized);
            $this->afterHook('buildPayload', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.buildPayload.success');

            return $this->success('buildPayload', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.buildPayload.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.buildPayload.failure');
            return $this->failure('buildPayload', [$e->getMessage()], $startedAt);
        }
    }

    public function transformInput(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.transformInput.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'transformInput');
            if (!$pre['ok']) {
                return $this->failure('transformInput', $pre['errors'], $startedAt);
            }

            $result = $this->perform('transformInput', $normalized);
            $this->afterHook('transformInput', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.transformInput.success');

            return $this->success('transformInput', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.transformInput.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.transformInput.failure');
            return $this->failure('transformInput', [$e->getMessage()], $startedAt);
        }
    }

    public function transformOutput(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.transformOutput.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'transformOutput');
            if (!$pre['ok']) {
                return $this->failure('transformOutput', $pre['errors'], $startedAt);
            }

            $result = $this->perform('transformOutput', $normalized);
            $this->afterHook('transformOutput', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.transformOutput.success');

            return $this->success('transformOutput', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.transformOutput.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.transformOutput.failure');
            return $this->failure('transformOutput', [$e->getMessage()], $startedAt);
        }
    }

    public function checkPreconditions(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.checkPreconditions.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'checkPreconditions');
            if (!$pre['ok']) {
                return $this->failure('checkPreconditions', $pre['errors'], $startedAt);
            }

            $result = $this->perform('checkPreconditions', $normalized);
            $this->afterHook('checkPreconditions', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.checkPreconditions.success');

            return $this->success('checkPreconditions', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.checkPreconditions.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.checkPreconditions.failure');
            return $this->failure('checkPreconditions', [$e->getMessage()], $startedAt);
        }
    }

    public function checkPostconditions(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.checkPostconditions.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'checkPostconditions');
            if (!$pre['ok']) {
                return $this->failure('checkPostconditions', $pre['errors'], $startedAt);
            }

            $result = $this->perform('checkPostconditions', $normalized);
            $this->afterHook('checkPostconditions', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.checkPostconditions.success');

            return $this->success('checkPostconditions', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.checkPostconditions.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.checkPostconditions.failure');
            return $this->failure('checkPostconditions', [$e->getMessage()], $startedAt);
        }
    }

    public function reserveResources(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.reserveResources.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'reserveResources');
            if (!$pre['ok']) {
                return $this->failure('reserveResources', $pre['errors'], $startedAt);
            }

            $result = $this->perform('reserveResources', $normalized);
            $this->afterHook('reserveResources', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.reserveResources.success');

            return $this->success('reserveResources', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.reserveResources.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.reserveResources.failure');
            return $this->failure('reserveResources', [$e->getMessage()], $startedAt);
        }
    }

    public function releaseResources(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.releaseResources.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'releaseResources');
            if (!$pre['ok']) {
                return $this->failure('releaseResources', $pre['errors'], $startedAt);
            }

            $result = $this->perform('releaseResources', $normalized);
            $this->afterHook('releaseResources', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.releaseResources.success');

            return $this->success('releaseResources', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.releaseResources.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.releaseResources.failure');
            return $this->failure('releaseResources', [$e->getMessage()], $startedAt);
        }
    }

    public function lockEntity(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.lockEntity.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'lockEntity');
            if (!$pre['ok']) {
                return $this->failure('lockEntity', $pre['errors'], $startedAt);
            }

            $result = $this->perform('lockEntity', $normalized);
            $this->afterHook('lockEntity', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.lockEntity.success');

            return $this->success('lockEntity', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.lockEntity.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.lockEntity.failure');
            return $this->failure('lockEntity', [$e->getMessage()], $startedAt);
        }
    }

    public function unlockEntity(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.unlockEntity.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'unlockEntity');
            if (!$pre['ok']) {
                return $this->failure('unlockEntity', $pre['errors'], $startedAt);
            }

            $result = $this->perform('unlockEntity', $normalized);
            $this->afterHook('unlockEntity', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.unlockEntity.success');

            return $this->success('unlockEntity', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.unlockEntity.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.unlockEntity.failure');
            return $this->failure('unlockEntity', [$e->getMessage()], $startedAt);
        }
    }

    public function queueFollowUp(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.queueFollowUp.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'queueFollowUp');
            if (!$pre['ok']) {
                return $this->failure('queueFollowUp', $pre['errors'], $startedAt);
            }

            $result = $this->perform('queueFollowUp', $normalized);
            $this->afterHook('queueFollowUp', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.queueFollowUp.success');

            return $this->success('queueFollowUp', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.queueFollowUp.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.queueFollowUp.failure');
            return $this->failure('queueFollowUp', [$e->getMessage()], $startedAt);
        }
    }

    public function scheduleRetry(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.scheduleRetry.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'scheduleRetry');
            if (!$pre['ok']) {
                return $this->failure('scheduleRetry', $pre['errors'], $startedAt);
            }

            $result = $this->perform('scheduleRetry', $normalized);
            $this->afterHook('scheduleRetry', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.scheduleRetry.success');

            return $this->success('scheduleRetry', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.scheduleRetry.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.scheduleRetry.failure');
            return $this->failure('scheduleRetry', [$e->getMessage()], $startedAt);
        }
    }

    public function markComplete(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.markComplete.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'markComplete');
            if (!$pre['ok']) {
                return $this->failure('markComplete', $pre['errors'], $startedAt);
            }

            $result = $this->perform('markComplete', $normalized);
            $this->afterHook('markComplete', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.markComplete.success');

            return $this->success('markComplete', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.markComplete.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.markComplete.failure');
            return $this->failure('markComplete', [$e->getMessage()], $startedAt);
        }
    }

    public function markFailed(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('KpiAggregator.markFailed.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'markFailed');
            if (!$pre['ok']) {
                return $this->failure('markFailed', $pre['errors'], $startedAt);
            }

            $result = $this->perform('markFailed', $normalized);
            $this->afterHook('markFailed', $normalized, $result);
            $this->metrics->increment('domain.Analytics.KpiAggregator.markFailed.success');

            return $this->success('markFailed', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('KpiAggregator.markFailed.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.Analytics.KpiAggregator.markFailed.failure');
            return $this->failure('markFailed', [$e->getMessage()], $startedAt);
        }
    }


    protected function normalizeContext(array $context): array
    {
        $normalized = [
            'airline_id' => (int) ($context['airline_id'] ?? 0),
            'station_code' => strtoupper((string) ($context['station_code'] ?? '')),
            'actor_id' => (int) ($context['actor_id'] ?? 0),
            'correlation_id' => $context['correlation_id'] ?? (string) Str::uuid(),
            'dry_run' => (bool) ($context['dry_run'] ?? false),
            'payload' => $context['payload'] ?? [],
            'options' => array_merge([
                'timeout_ms' => 5000,
                'retries' => 1,
                'priority' => 'normal',
            ], $context['options'] ?? []),
            'meta' => $context['meta'] ?? [],
        ];

        if ($normalized['airline_id'] <= 0) {
            throw new \InvalidArgumentException('airline_id is required');
        }

        return $normalized;
    }

    protected function runGuards(array $context, string $verb): array
    {
        $errors = [];
        if (empty($context['station_code']) && in_array($verb, ['execute', 'publish', 'dispatch', 'reserveResources'], true)) {
            $errors[] = 'station_code required for '.$verb;
        }
        if (($context['options']['priority'] ?? '') === 'critical' && empty($context['actor_id'])) {
            $errors[] = 'actor_id required for critical priority operations';
        }
        return ['ok' => empty($errors), 'errors' => $errors];
    }

    protected function perform(string $verb, array $context): array
    {
        $cacheKey = sprintf('domain:Analytics:KpiAggregator:%s:%s', $verb, md5(json_encode($context['payload'])));

        if (in_array($verb, ['forecast', 'computeMetrics', 'aggregate', 'simulate'], true)) {
            return Cache::remember($cacheKey, 60, function () use ($verb, $context) {
                return $this->compute($verb, $context);
            });
        }

        if ($context['dry_run']) {
            return [
                'dry_run' => true,
                'would_apply' => $this->compute($verb, $context),
            ];
        }

        return DB::transaction(function () use ($verb, $context) {
            return $this->compute($verb, $context);
        });
    }

    protected function compute(string $verb, array $context): array
    {
        $payload = $context['payload'];
        $items = $payload['items'] ?? [];
        $score = 0.0;
        foreach ($items as $item) {
            $weight = (float) ($item['weight'] ?? 1);
            $factor = (float) ($item['factor'] ?? 1);
            $score += $weight * $factor;
        }

        return [
            'service' => 'KpiAggregator',
            'module' => 'Analytics',
            'verb' => $verb,
            'correlation_id' => $context['correlation_id'],
            'airline_id' => $context['airline_id'],
            'station_code' => $context['station_code'],
            'item_count' => count($items),
            'score' => round($score, 4),
            'recommendations' => $this->buildRecommendations($verb, $score, $payload),
            'artifacts' => [
                'snapshot_id' => (string) Str::uuid(),
                'generated_at' => now()->toIso8601String(),
            ],
        ];
    }

    protected function buildRecommendations(string $verb, float $score, array $payload): array
    {
        $recs = [];
        if ($score > 100) {
            $recs[] = ['code' => 'HIGH_LOAD', 'message' => 'High operational load detected for KpiAggregator'];
        }
        if ($score < 5 && in_array($verb, ['optimize', 'forecast'], true)) {
            $recs[] = ['code' => 'LOW_SIGNAL', 'message' => 'Insufficient signal to optimize confidently'];
        }
        if (!empty($payload['force_manual_review'])) {
            $recs[] = ['code' => 'MANUAL_REVIEW', 'message' => 'Manual review flagged by caller'];
        }
        return $recs;
    }

    protected function afterHook(string $verb, array $context, array $result): void
    {
        if (in_array($verb, ['publish', 'broadcast', 'escalate'], true)) {
            $this->logger->info('KpiAggregator.after.'.$verb, [
                'correlation_id' => $context['correlation_id'],
                'score' => $result['score'] ?? null,
            ]);
        }
    }

    protected function success(string $verb, array $result, float $startedAt): array
    {
        return [
            'ok' => true,
            'verb' => $verb,
            'duration_ms' => round((microtime(true) - $startedAt) * 1000, 2),
            'result' => $result,
        ];
    }

    protected function failure(string $verb, array $errors, float $startedAt): array
    {
        return [
            'ok' => false,
            'verb' => $verb,
            'duration_ms' => round((microtime(true) - $startedAt) * 1000, 2),
            'errors' => $errors,
        ];
    }
}
