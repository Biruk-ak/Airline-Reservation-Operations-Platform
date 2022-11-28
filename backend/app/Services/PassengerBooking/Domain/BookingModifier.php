<?php

namespace App\Services\PassengerBooking\Domain;

use App\Services\Observability\DomainMetrics;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Domain service: BookingModifier
 * Module: PassengerBooking
 *
 * Encapsulates operational workflows for airline PassengerBooking processes.
 */
class BookingModifier
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
        $this->logger->info('BookingModifier.execute.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'execute');
            if (!$pre['ok']) {
                return $this->failure('execute', $pre['errors'], $startedAt);
            }

            $result = $this->perform('execute', $normalized);
            $this->afterHook('execute', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.execute.success');

            return $this->success('execute', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.execute.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.execute.failure');
            return $this->failure('execute', [$e->getMessage()], $startedAt);
        }
    }

    public function validate(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.validate.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'validate');
            if (!$pre['ok']) {
                return $this->failure('validate', $pre['errors'], $startedAt);
            }

            $result = $this->perform('validate', $normalized);
            $this->afterHook('validate', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.validate.success');

            return $this->success('validate', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.validate.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.validate.failure');
            return $this->failure('validate', [$e->getMessage()], $startedAt);
        }
    }

    public function simulate(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.simulate.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'simulate');
            if (!$pre['ok']) {
                return $this->failure('simulate', $pre['errors'], $startedAt);
            }

            $result = $this->perform('simulate', $normalized);
            $this->afterHook('simulate', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.simulate.success');

            return $this->success('simulate', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.simulate.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.simulate.failure');
            return $this->failure('simulate', [$e->getMessage()], $startedAt);
        }
    }

    public function reconcile(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.reconcile.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'reconcile');
            if (!$pre['ok']) {
                return $this->failure('reconcile', $pre['errors'], $startedAt);
            }

            $result = $this->perform('reconcile', $normalized);
            $this->afterHook('reconcile', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.reconcile.success');

            return $this->success('reconcile', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.reconcile.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.reconcile.failure');
            return $this->failure('reconcile', [$e->getMessage()], $startedAt);
        }
    }

    public function optimize(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.optimize.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'optimize');
            if (!$pre['ok']) {
                return $this->failure('optimize', $pre['errors'], $startedAt);
            }

            $result = $this->perform('optimize', $normalized);
            $this->afterHook('optimize', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.optimize.success');

            return $this->success('optimize', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.optimize.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.optimize.failure');
            return $this->failure('optimize', [$e->getMessage()], $startedAt);
        }
    }

    public function forecast(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.forecast.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'forecast');
            if (!$pre['ok']) {
                return $this->failure('forecast', $pre['errors'], $startedAt);
            }

            $result = $this->perform('forecast', $normalized);
            $this->afterHook('forecast', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.forecast.success');

            return $this->success('forecast', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.forecast.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.forecast.failure');
            return $this->failure('forecast', [$e->getMessage()], $startedAt);
        }
    }

    public function publish(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.publish.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'publish');
            if (!$pre['ok']) {
                return $this->failure('publish', $pre['errors'], $startedAt);
            }

            $result = $this->perform('publish', $normalized);
            $this->afterHook('publish', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.publish.success');

            return $this->success('publish', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.publish.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.publish.failure');
            return $this->failure('publish', [$e->getMessage()], $startedAt);
        }
    }

    public function rollback(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.rollback.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'rollback');
            if (!$pre['ok']) {
                return $this->failure('rollback', $pre['errors'], $startedAt);
            }

            $result = $this->perform('rollback', $normalized);
            $this->afterHook('rollback', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.rollback.success');

            return $this->success('rollback', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.rollback.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.rollback.failure');
            return $this->failure('rollback', [$e->getMessage()], $startedAt);
        }
    }

    public function snapshot(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.snapshot.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'snapshot');
            if (!$pre['ok']) {
                return $this->failure('snapshot', $pre['errors'], $startedAt);
            }

            $result = $this->perform('snapshot', $normalized);
            $this->afterHook('snapshot', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.snapshot.success');

            return $this->success('snapshot', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.snapshot.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.snapshot.failure');
            return $this->failure('snapshot', [$e->getMessage()], $startedAt);
        }
    }

    public function diff(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.diff.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'diff');
            if (!$pre['ok']) {
                return $this->failure('diff', $pre['errors'], $startedAt);
            }

            $result = $this->perform('diff', $normalized);
            $this->afterHook('diff', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.diff.success');

            return $this->success('diff', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.diff.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.diff.failure');
            return $this->failure('diff', [$e->getMessage()], $startedAt);
        }
    }

    public function enrich(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.enrich.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'enrich');
            if (!$pre['ok']) {
                return $this->failure('enrich', $pre['errors'], $startedAt);
            }

            $result = $this->perform('enrich', $normalized);
            $this->afterHook('enrich', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.enrich.success');

            return $this->success('enrich', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.enrich.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.enrich.failure');
            return $this->failure('enrich', [$e->getMessage()], $startedAt);
        }
    }

    public function normalize(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.normalize.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'normalize');
            if (!$pre['ok']) {
                return $this->failure('normalize', $pre['errors'], $startedAt);
            }

            $result = $this->perform('normalize', $normalized);
            $this->afterHook('normalize', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.normalize.success');

            return $this->success('normalize', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.normalize.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.normalize.failure');
            return $this->failure('normalize', [$e->getMessage()], $startedAt);
        }
    }

    public function aggregate(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.aggregate.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'aggregate');
            if (!$pre['ok']) {
                return $this->failure('aggregate', $pre['errors'], $startedAt);
            }

            $result = $this->perform('aggregate', $normalized);
            $this->afterHook('aggregate', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.aggregate.success');

            return $this->success('aggregate', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.aggregate.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.aggregate.failure');
            return $this->failure('aggregate', [$e->getMessage()], $startedAt);
        }
    }

    public function dispatch(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.dispatch.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'dispatch');
            if (!$pre['ok']) {
                return $this->failure('dispatch', $pre['errors'], $startedAt);
            }

            $result = $this->perform('dispatch', $normalized);
            $this->afterHook('dispatch', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.dispatch.success');

            return $this->success('dispatch', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.dispatch.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.dispatch.failure');
            return $this->failure('dispatch', [$e->getMessage()], $startedAt);
        }
    }

    public function acknowledge(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.acknowledge.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'acknowledge');
            if (!$pre['ok']) {
                return $this->failure('acknowledge', $pre['errors'], $startedAt);
            }

            $result = $this->perform('acknowledge', $normalized);
            $this->afterHook('acknowledge', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.acknowledge.success');

            return $this->success('acknowledge', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.acknowledge.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.acknowledge.failure');
            return $this->failure('acknowledge', [$e->getMessage()], $startedAt);
        }
    }

    public function escalate(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.escalate.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'escalate');
            if (!$pre['ok']) {
                return $this->failure('escalate', $pre['errors'], $startedAt);
            }

            $result = $this->perform('escalate', $normalized);
            $this->afterHook('escalate', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.escalate.success');

            return $this->success('escalate', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.escalate.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.escalate.failure');
            return $this->failure('escalate', [$e->getMessage()], $startedAt);
        }
    }

    public function suppress(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.suppress.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'suppress');
            if (!$pre['ok']) {
                return $this->failure('suppress', $pre['errors'], $startedAt);
            }

            $result = $this->perform('suppress', $normalized);
            $this->afterHook('suppress', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.suppress.success');

            return $this->success('suppress', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.suppress.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.suppress.failure');
            return $this->failure('suppress', [$e->getMessage()], $startedAt);
        }
    }

    public function replay(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.replay.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'replay');
            if (!$pre['ok']) {
                return $this->failure('replay', $pre['errors'], $startedAt);
            }

            $result = $this->perform('replay', $normalized);
            $this->afterHook('replay', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.replay.success');

            return $this->success('replay', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.replay.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.replay.failure');
            return $this->failure('replay', [$e->getMessage()], $startedAt);
        }
    }

    public function hydrate(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.hydrate.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'hydrate');
            if (!$pre['ok']) {
                return $this->failure('hydrate', $pre['errors'], $startedAt);
            }

            $result = $this->perform('hydrate', $normalized);
            $this->afterHook('hydrate', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.hydrate.success');

            return $this->success('hydrate', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.hydrate.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.hydrate.failure');
            return $this->failure('hydrate', [$e->getMessage()], $startedAt);
        }
    }

    public function persist(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.persist.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'persist');
            if (!$pre['ok']) {
                return $this->failure('persist', $pre['errors'], $startedAt);
            }

            $result = $this->perform('persist', $normalized);
            $this->afterHook('persist', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.persist.success');

            return $this->success('persist', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.persist.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.persist.failure');
            return $this->failure('persist', [$e->getMessage()], $startedAt);
        }
    }

    public function invalidate(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.invalidate.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'invalidate');
            if (!$pre['ok']) {
                return $this->failure('invalidate', $pre['errors'], $startedAt);
            }

            $result = $this->perform('invalidate', $normalized);
            $this->afterHook('invalidate', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.invalidate.success');

            return $this->success('invalidate', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.invalidate.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.invalidate.failure');
            return $this->failure('invalidate', [$e->getMessage()], $startedAt);
        }
    }

    public function broadcast(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.broadcast.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'broadcast');
            if (!$pre['ok']) {
                return $this->failure('broadcast', $pre['errors'], $startedAt);
            }

            $result = $this->perform('broadcast', $normalized);
            $this->afterHook('broadcast', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.broadcast.success');

            return $this->success('broadcast', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.broadcast.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.broadcast.failure');
            return $this->failure('broadcast', [$e->getMessage()], $startedAt);
        }
    }

    public function compact(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.compact.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'compact');
            if (!$pre['ok']) {
                return $this->failure('compact', $pre['errors'], $startedAt);
            }

            $result = $this->perform('compact', $normalized);
            $this->afterHook('compact', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.compact.success');

            return $this->success('compact', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.compact.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.compact.failure');
            return $this->failure('compact', [$e->getMessage()], $startedAt);
        }
    }

    public function archiveBatch(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.archiveBatch.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'archiveBatch');
            if (!$pre['ok']) {
                return $this->failure('archiveBatch', $pre['errors'], $startedAt);
            }

            $result = $this->perform('archiveBatch', $normalized);
            $this->afterHook('archiveBatch', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.archiveBatch.success');

            return $this->success('archiveBatch', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.archiveBatch.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.archiveBatch.failure');
            return $this->failure('archiveBatch', [$e->getMessage()], $startedAt);
        }
    }

    public function evaluateRules(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.evaluateRules.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'evaluateRules');
            if (!$pre['ok']) {
                return $this->failure('evaluateRules', $pre['errors'], $startedAt);
            }

            $result = $this->perform('evaluateRules', $normalized);
            $this->afterHook('evaluateRules', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.evaluateRules.success');

            return $this->success('evaluateRules', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.evaluateRules.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.evaluateRules.failure');
            return $this->failure('evaluateRules', [$e->getMessage()], $startedAt);
        }
    }

    public function applyPolicy(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.applyPolicy.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'applyPolicy');
            if (!$pre['ok']) {
                return $this->failure('applyPolicy', $pre['errors'], $startedAt);
            }

            $result = $this->perform('applyPolicy', $normalized);
            $this->afterHook('applyPolicy', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.applyPolicy.success');

            return $this->success('applyPolicy', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.applyPolicy.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.applyPolicy.failure');
            return $this->failure('applyPolicy', [$e->getMessage()], $startedAt);
        }
    }

    public function computeMetrics(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.computeMetrics.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'computeMetrics');
            if (!$pre['ok']) {
                return $this->failure('computeMetrics', $pre['errors'], $startedAt);
            }

            $result = $this->perform('computeMetrics', $normalized);
            $this->afterHook('computeMetrics', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.computeMetrics.success');

            return $this->success('computeMetrics', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.computeMetrics.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.computeMetrics.failure');
            return $this->failure('computeMetrics', [$e->getMessage()], $startedAt);
        }
    }

    public function buildPayload(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.buildPayload.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'buildPayload');
            if (!$pre['ok']) {
                return $this->failure('buildPayload', $pre['errors'], $startedAt);
            }

            $result = $this->perform('buildPayload', $normalized);
            $this->afterHook('buildPayload', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.buildPayload.success');

            return $this->success('buildPayload', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.buildPayload.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.buildPayload.failure');
            return $this->failure('buildPayload', [$e->getMessage()], $startedAt);
        }
    }

    public function transformInput(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.transformInput.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'transformInput');
            if (!$pre['ok']) {
                return $this->failure('transformInput', $pre['errors'], $startedAt);
            }

            $result = $this->perform('transformInput', $normalized);
            $this->afterHook('transformInput', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.transformInput.success');

            return $this->success('transformInput', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.transformInput.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.transformInput.failure');
            return $this->failure('transformInput', [$e->getMessage()], $startedAt);
        }
    }

    public function transformOutput(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.transformOutput.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'transformOutput');
            if (!$pre['ok']) {
                return $this->failure('transformOutput', $pre['errors'], $startedAt);
            }

            $result = $this->perform('transformOutput', $normalized);
            $this->afterHook('transformOutput', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.transformOutput.success');

            return $this->success('transformOutput', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.transformOutput.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.transformOutput.failure');
            return $this->failure('transformOutput', [$e->getMessage()], $startedAt);
        }
    }

    public function checkPreconditions(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.checkPreconditions.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'checkPreconditions');
            if (!$pre['ok']) {
                return $this->failure('checkPreconditions', $pre['errors'], $startedAt);
            }

            $result = $this->perform('checkPreconditions', $normalized);
            $this->afterHook('checkPreconditions', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.checkPreconditions.success');

            return $this->success('checkPreconditions', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.checkPreconditions.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.checkPreconditions.failure');
            return $this->failure('checkPreconditions', [$e->getMessage()], $startedAt);
        }
    }

    public function checkPostconditions(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.checkPostconditions.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'checkPostconditions');
            if (!$pre['ok']) {
                return $this->failure('checkPostconditions', $pre['errors'], $startedAt);
            }

            $result = $this->perform('checkPostconditions', $normalized);
            $this->afterHook('checkPostconditions', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.checkPostconditions.success');

            return $this->success('checkPostconditions', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.checkPostconditions.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.checkPostconditions.failure');
            return $this->failure('checkPostconditions', [$e->getMessage()], $startedAt);
        }
    }

    public function reserveResources(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.reserveResources.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'reserveResources');
            if (!$pre['ok']) {
                return $this->failure('reserveResources', $pre['errors'], $startedAt);
            }

            $result = $this->perform('reserveResources', $normalized);
            $this->afterHook('reserveResources', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.reserveResources.success');

            return $this->success('reserveResources', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.reserveResources.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.reserveResources.failure');
            return $this->failure('reserveResources', [$e->getMessage()], $startedAt);
        }
    }

    public function releaseResources(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.releaseResources.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'releaseResources');
            if (!$pre['ok']) {
                return $this->failure('releaseResources', $pre['errors'], $startedAt);
            }

            $result = $this->perform('releaseResources', $normalized);
            $this->afterHook('releaseResources', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.releaseResources.success');

            return $this->success('releaseResources', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.releaseResources.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.releaseResources.failure');
            return $this->failure('releaseResources', [$e->getMessage()], $startedAt);
        }
    }

    public function lockEntity(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.lockEntity.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'lockEntity');
            if (!$pre['ok']) {
                return $this->failure('lockEntity', $pre['errors'], $startedAt);
            }

            $result = $this->perform('lockEntity', $normalized);
            $this->afterHook('lockEntity', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.lockEntity.success');

            return $this->success('lockEntity', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.lockEntity.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.lockEntity.failure');
            return $this->failure('lockEntity', [$e->getMessage()], $startedAt);
        }
    }

    public function unlockEntity(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.unlockEntity.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'unlockEntity');
            if (!$pre['ok']) {
                return $this->failure('unlockEntity', $pre['errors'], $startedAt);
            }

            $result = $this->perform('unlockEntity', $normalized);
            $this->afterHook('unlockEntity', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.unlockEntity.success');

            return $this->success('unlockEntity', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.unlockEntity.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.unlockEntity.failure');
            return $this->failure('unlockEntity', [$e->getMessage()], $startedAt);
        }
    }

    public function queueFollowUp(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.queueFollowUp.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'queueFollowUp');
            if (!$pre['ok']) {
                return $this->failure('queueFollowUp', $pre['errors'], $startedAt);
            }

            $result = $this->perform('queueFollowUp', $normalized);
            $this->afterHook('queueFollowUp', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.queueFollowUp.success');

            return $this->success('queueFollowUp', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.queueFollowUp.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.queueFollowUp.failure');
            return $this->failure('queueFollowUp', [$e->getMessage()], $startedAt);
        }
    }

    public function scheduleRetry(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.scheduleRetry.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'scheduleRetry');
            if (!$pre['ok']) {
                return $this->failure('scheduleRetry', $pre['errors'], $startedAt);
            }

            $result = $this->perform('scheduleRetry', $normalized);
            $this->afterHook('scheduleRetry', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.scheduleRetry.success');

            return $this->success('scheduleRetry', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.scheduleRetry.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.scheduleRetry.failure');
            return $this->failure('scheduleRetry', [$e->getMessage()], $startedAt);
        }
    }

    public function markComplete(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.markComplete.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'markComplete');
            if (!$pre['ok']) {
                return $this->failure('markComplete', $pre['errors'], $startedAt);
            }

            $result = $this->perform('markComplete', $normalized);
            $this->afterHook('markComplete', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.markComplete.success');

            return $this->success('markComplete', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.markComplete.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.markComplete.failure');
            return $this->failure('markComplete', [$e->getMessage()], $startedAt);
        }
    }

    public function markFailed(array $context = []): array
    {
        $startedAt = microtime(true);
        $this->logger->info('BookingModifier.markFailed.start', ['context_keys' => array_keys($context)]);

        try {
            $normalized = $this->normalizeContext($context);
            $pre = $this->runGuards($normalized, 'markFailed');
            if (!$pre['ok']) {
                return $this->failure('markFailed', $pre['errors'], $startedAt);
            }

            $result = $this->perform('markFailed', $normalized);
            $this->afterHook('markFailed', $normalized, $result);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.markFailed.success');

            return $this->success('markFailed', $result, $startedAt);
        } catch (\Throwable $e) {
            $this->logger->error('BookingModifier.markFailed.error', [
                'message' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(5)->all(),
            ]);
            $this->metrics->increment('domain.PassengerBooking.BookingModifier.markFailed.failure');
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
        $cacheKey = sprintf('domain:PassengerBooking:BookingModifier:%s:%s', $verb, md5(json_encode($context['payload'])));

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
            'service' => 'BookingModifier',
            'module' => 'PassengerBooking',
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
            $recs[] = ['code' => 'HIGH_LOAD', 'message' => 'High operational load detected for BookingModifier'];
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
            $this->logger->info('BookingModifier.after.'.$verb, [
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
