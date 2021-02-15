import { Component, OnDestroy, OnInit } from '@angular/core';
import { Observable, Subscription } from 'rxjs';
import { tap } from 'rxjs/operators';

import { FamilyLog } from '../../../../../../../common/model/family-log.model';
import { FamilyLogService } from '../../services/family-log.service';

@Component({
  templateUrl: './list.template.html',
  styleUrls: ['./list.styles.scss'],
})
export class ListComponent implements OnInit, OnDestroy {
  public familyLogs$: Observable<Array<FamilyLog>> = this.service.familyLogs$;
  private readonly subscription: Subscription = new Subscription();

  constructor(private service: FamilyLogService) {}

  ngOnInit(): void {
    this.subscription.add(
      this.service.getFamilyLogs()
        .pipe(tap())
        .subscribe(),
    );
  }

  ngOnDestroy(): void {
    this.subscription.unsubscribe();
  }
}