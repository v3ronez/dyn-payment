<?php

declare(strict_types=1);

namespace App\Http\Wallet;

use App\Domain\User\Entity\User;
use App\Domain\Wallet\Actions\CreateWallet;
use App\Domain\Wallet\Actions\UpdateWallet;
use App\Domain\Wallet\DTOs\UpdateWalletDTO;
use App\Domain\Wallet\DTOs\WalletDTO;
use App\Domain\Wallet\Entity\Wallet;
use App\Domain\Wallet\Enums\WalletStatus;
use App\Domain\Wallet\Enums\WalletType;
use App\Domain\Wallet\FormRequests\StoreWalletRequest;
use App\Domain\Wallet\FormRequests\UpdateRequest;
use App\Http\Controllers\Controller;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class WalletController extends Controller
{
    public function store(StoreWalletRequest $request, #[CurrentUser] User $user)
    {

        try {
            $action = (new CreateWallet(
                $user,
                new WalletDTO(
                    $request->name,
                    $request->balance,
                    WalletStatus::tryFrom($request->status),
                    WalletType::Wallet
                ),
            ))->execute();
            if ($action->hasError()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Wallet not found',
                    'data' => null,
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Wallet stored successfully',
                'data' => $action->getSuccess(),
            ], Response::HTTP_OK);
        } catch (Throwable $e) {
            dd($e->getMessage(), $e->getFile(), $e->getLine());
            Log::error(
                "[Error] It's not possible to store this wallet: user document: {document} - error: {error}",
                [
                    'document' => substr($user->document_id->toString(), 0, 9).'***',
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );

            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => 'Something went wrong',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /* public function update(UpdateRequest $request, Wallet $wallet) */
    /* { */
    /*     try { */
    /*         $action = (new UpdateWallet( */
    /*             $wallet, */
    /*             new UpdateWalletDTO( */
    /*                 $request->name, */
    /*                 $request->balance, */
    /*                 $request->status, */
    /*             ), */
    /*         ))->execute(); */
    /*         if ($action->hasError()) { */
    /*             return response()->json([ */
    /*                 'status' => 'error', */
    /*                 'message' => 'Wallet not found', */
    /*                 'data' => null, */
    /*             ], Response::HTTP_NOT_FOUND); */
    /*         } */
    /**/
    /*         return response()->json([ */
    /*             'status' => 'success', */
    /*             'message' => 'Wallet updated successfully', */
    /*             'data' => $action->getSuccess()->getFormattedAttributes(), */
    /*         ], Response::HTTP_OK); */
    /*     } catch (Throwable $e) { */
    /*         Log::error( */
    /*             "[Error] It's not possible to update this wallet: {document}. error: {error}", */
    /*             [ */
    /*                 'document' => substr($wallet->document_id->toString(), 0, 9).'***', */
    /*                 'error' => $e->getMessage(), */
    /*                 'file' => $e->getFile(), */
    /*                 'line' => $e->getLine(), */
    /*             ] */
    /*         ); */
    /**/
    /*         return response()->json([ */
    /*             'status' => 'error', */
    /*             'data' => null, */
    /*             'message' => 'Something went wrong', */
    /*         ], Response::HTTP_INTERNAL_SERVER_ERROR); */
    /*     } */
    /* } */
    /**/
    /* public function destroy(Request $request, Wallet $wallet) */
    /* { */
    /*     try { */
    /*         $wallet = Wallet::first(); */
    /**/
    /*         throw new \Exception('Error'); */
    /*         $action = (new DeleteUser( */
    /*             $wallet */
    /*         ))->execute(); */
    /*         if ($action->hasError()) { */
    /*             return response()->json([ */
    /*                 'status' => 'error', */
    /*                 'message' => 'Wallet not found', */
    /*                 'data' => null, */
    /*             ], Response::HTTP_NOT_FOUND); */
    /*         } */
    /**/
    /*         return response()->json([ */
    /*             'status' => 'success', */
    /*             'message' => 'Wallet deleted successfully', */
    /*             'data' => $action->getSuccess(), */
    /*         ], Response::HTTP_OK); */
    /*     } catch (Throwable $e) { */
    /*         Log::error( */
    /*             "[Error] It's not possible to delete this wallet: {document}. error: {error}", */
    /*             [ */
    /*                 'document' => substr($wallet->document_id->toString(), 0, 9).'***', */
    /*                 'error' => $e->getMessage(), */
    /*                 'file' => $e->getFile(), */
    /*                 'line' => $e->getLine(), */
    /*             ] */
    /*         ); */
    /**/
    /*         return response()->json([ */
    /*             'status' => 'error', */
    /*             'data' => null, */
    /*             'message' => 'Something went wrong', */
    /*         ], Response::HTTP_INTERNAL_SERVER_ERROR); */
    /*     } */
    /* } */
}
